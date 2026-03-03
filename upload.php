<?php
require('../../config.php');
require_once($CFG->dirroot.'/local/customreg/lib.php');
require_login();
require_once($CFG->libdir . '/formslib.php');

$context = context_system::instance();

$PAGE->set_context($context);
$PAGE->set_url('/local/customreg/upload.php');
$PAGE->set_title('Registration Status');
$PAGE->set_heading('Registration Status');

// Check current user state. If we already uploaded but aren't approved yet, 
// show information screen instead of the upload form.
$rec = $DB->get_record('local_customreg', ['userid' => $USER->id]);
if ($rec && $rec->documentuploaded == 1 && $rec->status === 'pending') {
    echo $OUTPUT->header();
    echo $OUTPUT->notification('Thank you for uploading your Government ID.', 'notifysuccess');
    echo $OUTPUT->heading('Awaiting Administrator Review');
    
    echo '<p style="text-align:center; padding-top:20px;">Your registration is currently pending review by our administration team. 
           You will be notified once your account is approved and you can access your courses.</p>';
    
    echo '<div style="text-align:center; padding-top:40px;">';
    echo $OUTPUT->single_button(new moodle_url('/login/logout.php', ['sesskey' => sesskey()]), 'Logout');
    echo '</div>';
    
    echo $OUTPUT->footer();
    exit;
}

class local_customreg_upload_form extends moodleform {
    public function definition() {
        global $DB;
        $mform = $this->_form;

        // 1. Identity Type
        $mform->addElement('header', 'identityheader', 'Member Information');
        $mform->addElement('select', 'identitytype',
            get_string('areyouexisting', 'local_customreg'),
            [
                'existing' => get_string('existingmember', 'local_customreg'),
                'new' => get_string('newmember', 'local_customreg')
            ]
        );
        $mform->setDefault('identitytype', 'existing');

        // 2. Institution ID
        $mform->addElement('text', 'institutionid', get_string('institutionid', 'local_customreg'));
        $mform->setType('institutionid', PARAM_ALPHANUMEXT);
        $mform->hideIf('institutionid', 'identitytype', 'eq', 'new');

        // 3. Course Selection (Searchable)
        $availableids = [];
        for ($i = 1; $i <= 5; $i++) {
            $cid = (int)get_config('local_customreg', "course$i");
            if ($cid > 0) {
                $availableids[] = $cid;
            }
        }

        if (!empty($availableids)) {
            $options = [];
            foreach ($availableids as $cid) {
                if ($cid == 0) continue;
                $course = get_course($cid);
                if ($course) {
                    $options[$cid] = $course->fullname;
                }
            }
            
            if (!empty($options)) {
                $mform->addElement('header', 'courseheader', get_string('selectcourses', 'local_customreg'));
                
                // Using autocomplete with multiple=true for "searchable selection"
                $mform->addElement('autocomplete', 'customreg_courses', 
                    get_string('selectcourses', 'local_customreg'), 
                    $options, 
                    ['multiple' => true, 'noselectionstring' => 'Search and select courses...']
                );
                $mform->addRule('customreg_courses', null, 'required', null, 'client');
                $mform->addHelpButton('courseheader', 'availablecourses', 'local_customreg');
            }
        }

        // 4. File Picker
        $mform->addElement('header', 'fileheader', 'Verification Document');
        $mform->setExpanded('fileheader');
        
        $mform->addElement('filepicker', 'govid', 'Government ID', null, [
            'accepted_types' => ['.pdf', '.jpg', '.png']
        ]);
        $mform->addRule('govid', null, 'required', null, 'client');

        $this->add_action_buttons(false, 'Submit Registration');
    }

    public function validation($data, $files) {
        $errors = parent::validation($data, $files);
        
        // 1. Course count validation
        $count = 0;
        if (!empty($data['customreg_courses'])) {
            $count = count($data['customreg_courses']);
        }
        
        if ($count > 5) {
            $errors['customreg_courses'] = get_string('maxcoursesreached', 'local_customreg');
        } else if ($count == 0) {
            $errors['customreg_courses'] = get_string('atleastonecourse', 'local_customreg');
        }

        // 2. Server-side file extension validation
        $usercontext = context_user::instance($GLOBALS['USER']->id);
        $fs = get_file_storage();
        $draftid = file_get_submitted_draft_itemid('govid');
        $files_in_draft = $fs->get_area_files($usercontext->id, 'user', 'draft', $draftid, 'itemid', false);
        
        if (!empty($files_in_draft)) {
            $file = reset($files_in_draft);
            $filename = $file->get_filename();
            $allowed = ['.pdf', '.jpg', '.png', '.jpeg'];
            $ext = strtolower(strrchr($filename, '.'));
            if (!in_array($ext, $allowed)) {
                $errors['govid'] = 'Invalid file type. Only PDF, JPG, and PNG are allowed.';
            }
        }
        
        return $errors;
    }
}

$mform = new local_customreg_upload_form();

if ($rec) {
    $defaults = [
        'identitytype' => $rec->identitytype,
        'institutionid' => $rec->institutionid
    ];
    $courses = json_decode($rec->courseidsjson, true) ?: [];
    $defaults['customreg_courses'] = array_column($courses, 'id');
    $mform->set_data($defaults);
}

if ($data = $mform->get_data()) {
    $context = context_system::instance();
    $draftid = file_get_submitted_draft_itemid('govid');
    file_save_draft_area_files($draftid, $context->id, 'local_customreg', 'govid', $USER->id);

    // Extract courses from the autocomplete multiple select
    $selectedcourses = [];
    if (!empty($data->customreg_courses)) {
        foreach ($data->customreg_courses as $cid) {
            $cid = (int)$cid;
            if ($cid > 0) {
                $selectedcourses[] = [
                    'id' => $cid,
                    'status' => 'pending',
                    'timecreated' => time()
                ];
            }
        }
    }

    // Update the record
    $update = new stdClass();
    $update->userid = $USER->id;
    $update->identitytype = $data->identitytype;
    $update->institutionid = ($data->identitytype === 'new') ? 'NA' : $data->institutionid;
    $update->courseidsjson = json_encode($selectedcourses);
    $update->documentuploaded = 1;
    $update->status = 'pending';
    $update->timemodified = time();

    // Ensure record exists before updating
    $rec = $DB->get_record('local_customreg', ['userid' => $USER->id]);
    if (!$rec) {
        $update->timecreated = time();
        $DB->insert_record('local_customreg', $update);
    } else {
        $update->id = $rec->id;
        $DB->update_record('local_customreg', $update);
    }

    // Log the update
    local_customreg_log($USER->id, 'uploaded', 'User completed registration form and uploaded document.');

    // Notify admins
    local_customreg_notify_admins_new_upload($USER->id);

    echo $OUTPUT->header();
    echo $OUTPUT->notification('Document uploaded successfully.', 'notifysuccess');
    echo $OUTPUT->heading('Awaiting Administrator Review');
    echo '<p style="text-align:center; padding-top:20px;">Your document has been submitted and is currently being reviewed by our administrators. 
           You will be notified once the review is complete.</p>';
    
    echo '<div style="text-align:center; padding-top:40px;">';
    echo $OUTPUT->single_button(new moodle_url('/login/logout.php', ['sesskey' => sesskey()]), 'Logout');
    echo '</div>';
    
    echo $OUTPUT->footer();
    exit;
}

echo $OUTPUT->header();

if ($rec && $rec->status === 'rejected') {
    echo $OUTPUT->notification(get_string('uploadagain', 'local_customreg'), 'notifyproblem');
}

$mform->display();
echo $OUTPUT->footer();
