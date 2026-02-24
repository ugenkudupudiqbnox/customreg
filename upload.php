<?php
require('../../config.php');
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
        $mform = $this->_form;

        $mform->addElement('filepicker', 'govid', 'Government ID', null, [
            'accepted_types' => ['.pdf', '.jpg', '.png']
        ]);
        $mform->addRule('govid', null, 'required', null, 'client');

        $this->add_action_buttons(false, 'Upload');
    }
}

$mform = new local_customreg_upload_form();

if ($data = $mform->get_data()) {
    $draftid = file_get_submitted_draft_itemid('govid');
    file_save_draft_area_files($draftid, $context->id, 'local_customreg', 'govid', $USER->id);

    // Set back to pending even if previously denied
    $DB->set_field('local_customreg', 'documentuploaded', 1, ['userid' => $USER->id]);
    $DB->set_field('local_customreg', 'status', 'pending', ['userid' => $USER->id]);
    $DB->set_field('local_customreg', 'timemodified', time(), ['userid' => $USER->id]);

    // Log the upload
    require_once($CFG->dirroot . '/local/customreg/lib.php');
    local_customreg_log($USER->id, 'uploaded', 'User uploaded a new document for identification.');

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

if ($rec && $rec->status === 'denied') {
    echo $OUTPUT->notification(get_string('uploadagain', 'local_customreg'), 'notifyproblem');
}

$mform->display();
echo $OUTPUT->footer();
