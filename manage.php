<?php
require('../../config.php');
require_once($CFG->libdir.'/adminlib.php');
require_once($CFG->dirroot.'/local/customreg/lib.php');
require_login();

admin_externalpage_setup('local_customreg_manage');

$context = context_system::instance();

$action = optional_param('action', '', PARAM_ALPHANUMEXT);
$userid = optional_param('userid', 0, PARAM_INT);
$search = optional_param('search', '', PARAM_TEXT);
$page   = optional_param('page', 0, PARAM_INT);
$selectedusers = optional_param_array('selectedusers', [], PARAM_INT);
$perpage = 20;
$orderby = "CASE
                WHEN cr.status = 'pending' THEN 0
                WHEN cr.status = 'rejected' OR cr.status = 'denied' THEN 1
                WHEN cr.status = 'approved' THEN 3
                ELSE 2
            END, cr.timemodified DESC, cr.timecreated DESC";

// Handle CSV Export
if ($action === 'downloadcsv') {
    require_capability('local/customreg:manage', $context);
    
    // Build SQL for all records matches search but no pagination
    $params = [];
    $where = "1=1";
    if ($search) {
        $where .= " AND (u.firstname LIKE :s1 OR u.lastname LIKE :s2 OR u.email LIKE :s3)";
        $params['s1'] = '%'.$search.'%';
        $params['s2'] = '%'.$search.'%';
        $params['s3'] = '%'.$search.'%';
    }
    
        $sql = "SELECT cr.*, u.firstname, u.lastname, u.email 
              FROM {local_customreg} cr
              JOIN {user} u ON cr.userid = u.id
             WHERE $where
            ORDER BY $orderby";
          
    $records = $DB->get_records_sql($sql, $params);
    
    $filename = 'customreg_data_' . date('Ymd_His') . '.csv';
    header('Content-Type: text/csv; charset=utf-8');
    header('Content-Disposition: attachment; filename=' . $filename);
    
    $output = fopen('php://output', 'w');
    
    // Add UTF-8 BOM for Excel compatibility
    fprintf($output, chr(0xEF).chr(0xBB).chr(0xBF));
    
    // Header row
    fputcsv($output, [
        'User ID',
        get_string('csv_firstname', 'local_customreg'),
        get_string('csv_lastname', 'local_customreg'),
        get_string('csv_email', 'local_customreg'),
        'Student ID',
        get_string('csv_status', 'local_customreg'),
        get_string('csv_courses', 'local_customreg'),
        get_string('csv_timecreated', 'local_customreg')
    ]);
    
    foreach ($records as $rec) {
        // Format course list for CSV
        $coursesjson = json_decode($rec->courseidsjson, true) ?: [];
        $courselist = [];
        foreach ($coursesjson as $cinfo) {
            $course = $DB->get_record('course', ['id' => $cinfo['id']], 'shortname');
            if ($course) {
                $courselist[] = "{$course->shortname} ({$cinfo['status']})";
            }
        }
        
        fputcsv($output, [
            $rec->userid,
            $rec->firstname,
            $rec->lastname,
            $rec->email,
            $rec->institutionid,
            $rec->status,
            implode(' | ', $courselist),
            userdate($rec->timecreated, '%Y-%m-%d %H:%M:%S')
        ]);
    }
    
    fclose($output);
    exit;
}

// Handle Log Retrieval (AJAX-like response)
if ($action === 'getlogs' && $userid > 0) {
    require_capability('local/customreg:manage', $context);
    header('Content-Type: application/json');
    // Limit to latest 50 logs to avoid performance issues
    $logs = $DB->get_records_sql("
        SELECT l.*, u.firstname, u.lastname 
        FROM {local_customreg_logs} l 
        LEFT JOIN {user} u ON l.adminid = u.id 
        WHERE l.userid = ? 
        ORDER BY l.timecreated DESC", [$userid], 0, 50);
    
    $output = [];
    foreach ($logs as $log) {
        $admin = ($log->adminid == 0) ? 'System/User' : fullname($log);
        $output[] = [
            'date' => userdate($log->timecreated, get_string('strftimedatetimeshort', 'langconfig')),
            'action' => ucfirst($log->action),
            'admin' => $admin,
            'details' => $log->details
        ];
    }
    echo json_encode($output);
    exit;
}

// Handle Approval Action
if ($action === 'approve' && $userid > 0 && confirm_sesskey()) {
    $comments = optional_param('comments', '', PARAM_TEXT);
    if (empty($comments)) {
        $comments = get_string('default_approve_comment', 'local_customreg');
    }
    $DB->set_field('local_customreg', 'status', 'approved', ['userid' => $userid]);
    $DB->set_field('local_customreg', 'admin_comments', $comments, ['userid' => $userid]);
    $DB->set_field('local_customreg', 'timemodified', time(), ['userid' => $userid]);
    
    // Log approval
    local_customreg_log($userid, 'approved', 'Registration overall approved. Comments: ' . $comments);
    
    // Notify user - only for global registration status
    local_customreg_notify_user_status($userid, 'approved', $comments);

    redirect($PAGE->url, get_string('userapproved', 'local_customreg'), 2);
}

// Handle Deny Action
if ($action === 'deny' && $userid > 0 && confirm_sesskey()) {
    $comments = optional_param('comments', '', PARAM_TEXT);
    if (empty($comments)) {
        $comments = get_string('default_deny_comment', 'local_customreg');
    }
    $DB->set_field('local_customreg', 'status', 'rejected', ['userid' => $userid]);
    $DB->set_field('local_customreg', 'admin_comments', $comments, ['userid' => $userid]);
    $DB->set_field('local_customreg', 'documentuploaded', 0, ['userid' => $userid]);
    $DB->set_field('local_customreg', 'timemodified', time(), ['userid' => $userid]);
    
    // Log denial
    local_customreg_log($userid, 'rejected', 'Registration request rejected. Comments: ' . $comments);
    
    // Notify user
    local_customreg_notify_user_status($userid, 'rejected', $comments);

    // Optional: Delete existing files to save space and avoid confusion
    $fs = get_file_storage();
    $fs->delete_area_files($context->id, 'local_customreg', 'govid', $userid);
    
    redirect($PAGE->url, get_string('userdenied', 'local_customreg'), 2);
}

// Handle Individual Course Approval
if ($action === 'approvecourse' && $userid > 0 && confirm_sesskey()) {
    $courseid = required_param('courseid', PARAM_INT);
    $comments = optional_param('comments', '', PARAM_TEXT);
    if (empty($comments)) {
        $comments = get_string('default_approve_course_comment', 'local_customreg');
    }
    $rec = $DB->get_record('local_customreg', ['userid' => $userid], '*', MUST_EXIST);
    $courses = json_decode($rec->courseidsjson, true) ?: [];
    
    // Count already approved courses
    $approvedcount = 0;
    foreach ($courses as $c) {
        if ($c['status'] === 'approved') {
            $approvedcount++;
        }
    }

    if ($approvedcount >= 5) {
        redirect($PAGE->url, get_string('maxcoursesreached', 'local_customreg'), 2);
    }

    foreach ($courses as &$c) {
        if ($c['id'] == $courseid) {
            $c['status'] = 'approved';
            local_customreg_enroll_user_into_course($userid, $courseid);
            local_customreg_log($userid, 'approvecourse', "Course ID $courseid approved and user enrolled. Comments: $comments");
            
            // Notify user for single course - only if we are not planning to send a bulk notification elsewhere
            local_customreg_notify_course_approved($userid, $courseid, $comments);
        }
    }
    
    // Check if ALL courses are now approved. If so, update the global status to approved.
    $all_approved = true;
    foreach ($courses as $c) {
        if ($c['status'] !== 'approved') {
            $all_approved = false;
            break;
        }
    }
    
    if ($all_approved) {
        $DB->set_field('local_customreg', 'status', 'approved', ['userid' => $userid]);
        $DB->set_field('local_customreg', 'timemodified', time(), ['userid' => $userid]);
        // BEYOND FIX: We set courseidsjson BEFORE redirection so logic is sound. We 
        // will not trigger local_customreg_notify_user_status here because
        // local_customreg_notify_course_approved was just sent in the loop above.
    }
    
    $DB->set_field('local_customreg', 'courseidsjson', json_encode($courses), ['id' => $rec->id]);
    redirect($PAGE->url, get_string('enrollsuccess', 'local_customreg'), 2);
}

// Handle Individual Course Denial
if ($action === 'denycourse' && $userid > 0 && confirm_sesskey()) {
    $courseid = required_param('courseid', PARAM_INT);
    $comments = optional_param('comments', '', PARAM_TEXT);
    if (empty($comments)) {
        $comments = get_string('default_deny_course_comment', 'local_customreg');
    }
    $rec = $DB->get_record('local_customreg', ['userid' => $userid], '*', MUST_EXIST);
    $courses = json_decode($rec->courseidsjson, true) ?: [];
    
    foreach ($courses as &$c) {
        if ($c['id'] == $courseid) {
            $c['status'] = 'rejected';
            local_customreg_log($userid, 'denycourse', "Course ID $courseid rejected. Comments: $comments");
        }
    }
    
    // Check if ALL courses are now resolved (approved or rejected). 
    // If so, update the global status to 'approved' if at least one is approved, 
    // or 'rejected' if none are approved and all are rejected.
    $any_approved = false;
    $all_resolved = true;
    foreach ($courses as $c) {
        if ($c['status'] === 'approved') {
            $any_approved = true;
        } else if ($c['status'] === 'pending') {
            $all_resolved = false;
        }
    }

    if ($all_resolved) {
        $new_global_status = $any_approved ? 'approved' : 'rejected';
        $DB->set_field('local_customreg', 'status', $new_global_status, ['userid' => $userid]);
        $DB->set_field('local_customreg', 'timemodified', time(), ['userid' => $userid]);
    }

    $DB->set_field('local_customreg', 'courseidsjson', json_encode($courses), ['id' => $rec->id]);
    redirect($PAGE->url, get_string('userdenied', 'local_customreg'), 2);
}

// Handle Bulk Course Approval
if ($action === 'approveallcourses' && $userid > 0 && confirm_sesskey()) {
    $comments = optional_param('comments', '', PARAM_TEXT);
    if (empty($comments)) {
        $comments = get_string('default_approve_course_comment', 'local_customreg');
    }
    $rec = $DB->get_record('local_customreg', ['userid' => $userid], '*', MUST_EXIST);
    $courses = json_decode($rec->courseidsjson, true) ?: [];
    
    $newly_approved = [];
    foreach ($courses as &$c) {
        if ($c['status'] === 'pending') {
            $c['status'] = 'approved';
            local_customreg_enroll_user_into_course($userid, $c['id']);
            local_customreg_log($userid, 'approvecourse', "Course ID {$c['id']} approved via bulk action. Comments: $comments");
            $newly_approved[] = $c['id'];
        }
    }

    if (!empty($newly_approved)) {
        // Send ONE email for all newly approved courses in bulk
        local_customreg_notify_user_status($userid, 'approved', $comments, $newly_approved);
    }
    
    $DB->set_field('local_customreg', 'courseidsjson', json_encode($courses), ['id' => $rec->id]);
    redirect($PAGE->url, get_string('enrollsuccess', 'local_customreg'), 2);
}

// Handle Multi-user Bulk Approval
if ($action === 'bulkapprove' && confirm_sesskey()) {
    require_capability('local/customreg:manage', $context);

    $comments = optional_param('comments', '', PARAM_TEXT);
    if (empty($comments)) {
        $comments = get_string('default_approve_comment', 'local_customreg');
    }

    $selectedusers = array_filter(array_unique(array_map('intval', $selectedusers)));
    if (empty($selectedusers)) {
        redirect($PAGE->url, 'No users selected for bulk approval.', 2);
    }

    list($insql, $inparams) = $DB->get_in_or_equal($selectedusers, SQL_PARAMS_NAMED, 'uid');
    $records = $DB->get_records_sql("SELECT * FROM {local_customreg} WHERE userid $insql", $inparams);

    if (empty($records)) {
        redirect($PAGE->url, 'No matching users found for bulk approval.', 2);
    }

    $approveduserscount = 0;
    $coursesapprovedcount = 0;
    $enrolledcount = 0;
    $timenow = time();

    foreach ($records as $rec) {
        $courses = json_decode($rec->courseidsjson, true);
        if (!is_array($courses)) {
            $courses = [];
        }

        $newlyapprovedcourses = [];
        foreach ($courses as &$courseinfo) {
            if (!empty($courseinfo['status']) && $courseinfo['status'] === 'pending') {
                $courseinfo['status'] = 'approved';
                $newlyapprovedcourses[] = (int)$courseinfo['id'];
                $coursesapprovedcount++;

                $enrollstatus = local_customreg_enroll_user_into_course((int)$rec->userid, (int)$courseinfo['id']);
                if ($enrollstatus === 'enrollsuccess' || $enrollstatus === 'alreadyenrolled') {
                    $enrolledcount++;
                }

                local_customreg_log((int)$rec->userid, 'approvecourse', "Course ID {$courseinfo['id']} approved via bulk user selection. Comments: $comments");
            }
        }
        unset($courseinfo);

        $updaterecord = new stdClass();
        $updaterecord->id = $rec->id;
        $updaterecord->status = 'approved';
        $updaterecord->admin_comments = $comments;
        $updaterecord->verifiedby = $USER->id;
        $updaterecord->timeverified = $timenow;
        $updaterecord->timemodified = $timenow;
        $updaterecord->courseidsjson = json_encode($courses);
        $DB->update_record('local_customreg', $updaterecord);

        local_customreg_log((int)$rec->userid, 'approved', 'Registration approved via bulk action. Comments: ' . $comments);
        local_customreg_notify_user_status((int)$rec->userid, 'approved', $comments, $newlyapprovedcourses);
        $approveduserscount++;
    }

    $message = "Bulk approval completed. Users approved: {$approveduserscount}, courses approved: {$coursesapprovedcount}, enrollments processed: {$enrolledcount}.";
    redirect($PAGE->url, $message, 2);
}

echo $OUTPUT->header();

echo $OUTPUT->heading(get_string('manageusers', 'local_customreg'));

// Action bar with CSV Download
echo '<div class="d-flex justify-content-between align-items-center mb-4">';
$downloadurl = new moodle_url($PAGE->url, ['action' => 'downloadcsv', 'search' => $search]);
echo html_writer::link($downloadurl, $OUTPUT->pix_icon('t/download', '') . ' ' . get_string('downloadcsv', 'local_customreg'), ['class' => 'btn btn-secondary']);

// Search Bar
echo $OUTPUT->render_from_template('core/search_input', [
    'action' => $PAGE->url->out(false),
    'name' => 'search',
    'value' => $search,
    'placeholder' => get_string('searchusers', 'local_customreg'),
    'viewid' => 'search-users-input'
]);
echo '</div>';

// Build SQL
$params = [];
$where = "1=1";
if ($search) {
    $where .= " AND (u.firstname LIKE :s1 OR u.lastname LIKE :s2 OR u.email LIKE :s3)";
    $params['s1'] = '%'.$search.'%';
    $params['s2'] = '%'.$search.'%';
    $params['s3'] = '%'.$search.'%';
}

$sql = "SELECT cr.*, u.firstname, u.lastname, u.email 
          FROM {local_customreg} cr
          JOIN {user} u ON cr.userid = u.id
         WHERE $where
    ORDER BY $orderby";

$totalcount = $DB->count_records_sql("SELECT COUNT(*) FROM {local_customreg} cr JOIN {user} u ON cr.userid = u.id WHERE $where", $params);
$records = $DB->get_records_sql($sql, $params, $page * $perpage, $perpage);

if (!$records) {
    echo $OUTPUT->notification('No records found.', 'info');
} else {
    $defaultcomments = [
        'approve' => get_string('default_approve_comment', 'local_customreg'),
        'deny' => get_string('default_deny_comment', 'local_customreg'),
        'approvecourse' => get_string('default_approve_course_comment', 'local_customreg'),
        'denycourse' => get_string('default_deny_course_comment', 'local_customreg'),
        'approveallcourses' => get_string('default_approve_course_comment', 'local_customreg'),
        'bulkapprove' => get_string('default_approve_comment', 'local_customreg'),
    ];

    echo html_writer::start_tag('form', ['method' => 'post', 'action' => $PAGE->url->out(false)]);
    echo html_writer::empty_tag('input', ['type' => 'hidden', 'name' => 'sesskey', 'value' => sesskey()]);
    echo html_writer::empty_tag('input', ['type' => 'hidden', 'name' => 'action', 'value' => 'bulkapprove']);
    echo html_writer::empty_tag('input', ['type' => 'hidden', 'name' => 'search', 'value' => $search]);
    echo html_writer::empty_tag('input', ['type' => 'hidden', 'name' => 'page', 'value' => $page]);

    echo '<div class="mb-2">';
    echo html_writer::tag('button', 'Approve Selected (ID + Courses)', [
        'type' => 'button',
        'class' => 'btn btn-success action-with-comment',
        'data-action' => 'bulkapprove',
        'data-userid' => 0,
        'id' => 'bulk-approve-trigger'
    ]);
    echo '</div>';

    $table = new html_table();
    $table->head = [
        html_writer::empty_tag('input', ['type' => 'checkbox', 'id' => 'bulk-select-all', 'title' => 'Select all non-approved users']),
        'User',
        'Institution ID',
        get_string('documentstatus', 'local_customreg'),
        'Requested Courses',
        get_string('csv_timecreated', 'local_customreg'),
        get_string('action', 'local_customreg')
    ];

    foreach ($records as $rec) {
        $userlink = html_writer::link(new moodle_url('/user/profile.php', ['id' => $rec->userid]), fullname($rec));
        
        $statusstr = get_string($rec->status, 'local_customreg');
        $statuscolor = 'badge-warning';
        if ($rec->status === 'approved') {
            $statuscolor = 'badge-success';
        } else if ($rec->status === 'denied' || $rec->status === 'rejected') {
            $statuscolor = 'badge-danger';
        }
        $statusbadge = html_writer::tag('span', $statusstr, ['class' => 'badge ' . $statuscolor]);

        // ID Preview Logic
        $id_with_preview = s($rec->institutionid);
        if ($rec->documentuploaded) {
            $fs = get_file_storage();
            $files = $fs->get_area_files($context->id, 'local_customreg', 'govid', $rec->userid, 'id DESC', false);
            if ($files) {
                $file = reset($files);
                $url = moodle_url::make_pluginfile_url($file->get_contextid(), $file->get_component(), $file->get_filearea(), $file->get_itemid(), $file->get_filepath(), $file->get_filename());
                
                $id_with_preview .= ' ' . html_writer::link('#', $OUTPUT->pix_icon('t/preview', 'View ID'), [
                    'class' => 'view-id-trigger',
                    'data-url' => $url->out(false),
                    'title' => 'View ID'
                ]);
            }
        }

        // Course Selection Column Logic
        $coursesjson = json_decode($rec->courseidsjson, true) ?: [];
        $courselist = [];
        $haspending = false;

        foreach ($coursesjson as $cinfo) {
            $course = $DB->get_record('course', ['id' => $cinfo['id']]);
            if (!$course) continue;
            
            if ($cinfo['status'] === 'pending') {
                $haspending = true;
                $statusclass = 'badge-warning';
                
                // Individual course buttons
                $approvecurl = new moodle_url($PAGE->url, [
                    'action' => 'approvecourse', 
                    'userid' => $rec->userid, 
                    'courseid' => $cinfo['id'], 
                    'sesskey' => sesskey()
                ]);
                $denycurl = new moodle_url($PAGE->url, [
                    'action' => 'denycourse', 
                    'userid' => $rec->userid, 
                    'courseid' => $cinfo['id'], 
                    'sesskey' => sesskey()
                ]);
                
                $citem = html_writer::tag('div', 
                    $course->fullname . ' ' .
                    html_writer::tag('span', 'Pending', ['class' => 'badge ' . $statusclass]) . ' ' .
                    $OUTPUT->action_icon('#', new pix_icon('t/check', 'Approve'), null, ['class' => 'action-with-comment', 'data-action' => 'approvecourse', 'data-userid' => $rec->userid, 'data-courseid' => $cinfo['id']]) .
                    $OUTPUT->action_icon('#', new pix_icon('t/delete', 'Deny'), null, ['class' => 'action-with-comment', 'data-action' => 'denycourse', 'data-userid' => $rec->userid, 'data-courseid' => $cinfo['id']]),
                    ['class' => 'mb-1 border-bottom pb-1']
                );
            } else {
                $statusclass = ($cinfo['status'] === 'approved') ? 'badge-success' : 'badge-danger';
                $citem = html_writer::tag('div', 
                    $course->fullname . ' ' .
                    html_writer::tag('span', ucfirst($cinfo['status']), ['class' => 'badge ' . $statusclass]),
                    ['class' => 'mb-1 border-bottom pb-1']
                );
            }
            $courselist[] = $citem;
        }

        $courseinfo = implode('', $courselist);
        if ($haspending) {
            $courseinfo .= html_writer::link('#', 'Approve All Courses', ['class' => 'btn btn-outline-success btn-sm mt-1 action-with-comment', 'data-action' => 'approveallcourses', 'data-userid' => $rec->userid]);
        }

        if (empty($courseinfo)) {
            $courseinfo = '<small>No courses selected</small>';
        }

        $actions = [];
        $rowcheckbox = '';

        if ($rec->status !== 'approved') {
            $rowcheckbox = html_writer::empty_tag('input', [
                'type' => 'checkbox',
                'name' => 'selectedusers[]',
                'value' => $rec->userid,
                'class' => 'bulk-user-select'
            ]);
        }

        if ($rec->status !== 'approved') {
            $actions[] = $OUTPUT->action_icon('#', new pix_icon('t/check', get_string('approve', 'local_customreg')), null, ['class' => 'action-with-comment', 'data-action' => 'approve', 'data-userid' => $rec->userid]);
            $actions[] = $OUTPUT->action_icon('#', new pix_icon('i/invalid', get_string('deny', 'local_customreg')), null, ['class' => 'action-with-comment', 'data-action' => 'deny', 'data-userid' => $rec->userid]);
        }
        
        // Addition: Log History Icon
        $actions[] = html_writer::link('#', $OUTPUT->pix_icon('i/menu', 'View Timeline'), [
            'class' => 'view-log-trigger',
            'data-userid' => $rec->userid,
            'title' => 'Registration Timeline'
        ]);

        $table->data[] = [
            $rowcheckbox,
            $userlink . '<br><small>' . s($rec->email) . '</small>',
            $id_with_preview,
            $statusbadge,
            $courseinfo,
            userdate($rec->timecreated, get_string('strftimedatetimeshort', 'langconfig')),
            implode(' ', $actions)
        ];
    }

    echo html_writer::table($table);
    echo html_writer::end_tag('form');

    echo $OUTPUT->paging_bar($totalcount, $page, $perpage, $PAGE->url);
}

// Initialize the management page JavaScript using proper AMD module
$defaultcomments = [
    'approve' => get_string('default_approve_comment', 'local_customreg'),
    'deny' => get_string('default_deny_comment', 'local_customreg'),
    'approvecourse' => get_string('default_approve_course_comment', 'local_customreg'),
    'denycourse' => get_string('default_deny_course_comment', 'local_customreg'),
    'approveallcourses' => get_string('default_approve_course_comment', 'local_customreg'),
    'bulkapprove' => get_string('default_approve_comment', 'local_customreg'),
];
$PAGE->requires->js_call_amd('local_customreg/manage', 'init', [$defaultcomments]);

echo $OUTPUT->footer();
