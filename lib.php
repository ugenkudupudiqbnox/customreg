<?php
defined('MOODLE_INTERNAL') || die();

/**
 * Legacy hook for signup extension (DEPRECATED: MOVED TO POST-LOGIN)
 */
function local_customreg_extend_signup_form($mform) {
    // No longer extending the signup form.
}

/**
 * Log an action for a registration request
 */
function local_customreg_log($userid, $action, $details = null) {
    global $DB, $USER;
    
    $log = new stdClass();
    $log->userid = $userid;
    $log->adminid = $USER->id;
    $log->action = $action;
    $log->details = $details;
    $log->timecreated = time();
    $DB->insert_record('local_customreg_logs', $log);

    // Trigger standard Moodle Event for integration with site-wide reporting
    $event = \local_customreg\event\registration_updated::create([
        'context' => \context_system::instance(),
        'userid' => $USER->id,
        'relateduserid' => $userid,
        'other' => [
            'action' => $action,
            'details' => $details,
            'status' => $action // In many cases action matches the new status
        ]
    ]);
    $event->trigger();
}

/**
 * Notify site administrators about new ID upload
 */
function local_customreg_notify_admins_new_upload($userid) {
    global $DB, $CFG;

    $user = $DB->get_record('user', ['id' => $userid], '*', MUST_EXIST);
    $admins = get_admins();
    foreach ($admins as $admin) {
        $a = new stdClass();
        $a->username = fullname($user);
        $a->url = new moodle_url('/local/customreg/manage.php', ['userid' => $userid]);
        $subject = get_string('email_admin_subject', 'local_customreg', $a);
        $body = get_string('email_admin_body', 'local_customreg', $a);
        email_to_user($admin, core_user::get_noreply_user(), $subject, $body);
    }
}

/**
 * Notify user about registration status change
 */
function local_customreg_notify_user_status($userid, $status, $comments = '', $courses = []) {
    global $DB, $CFG;

    $user = $DB->get_record('user', ['id' => $userid], '*', MUST_EXIST);
    $a = new stdClass();
    $a->firstname = $user->firstname;
    $a->comments = !empty($comments) ? $comments : get_string('notapplicable', 'local_customreg');
    $a->sitelink = $CFG->wwwroot;

    if ($status === 'approved') {
        $a->courses = '';
        $is_bulk = false;
        if (!empty($courses)) {
            $is_bulk = true;
            $coursenames = [];
            foreach ($courses as $cid) {
                $c = $DB->get_record('course', ['id' => $cid]);
                if ($c) {
                    $courseurl = new moodle_url('/course/view.php', ['id' => $cid]);
                    $coursenames[] = "- " . $c->fullname . " (" . $courseurl->out(false) . ")";
                }
            }
            $a->courses = implode("\n", $coursenames);
        }
        
        if ($is_bulk) {
            $subject = get_string('email_bulk_approved_subject', 'local_customreg', $a);
            $body = get_string('email_bulk_approved_body', 'local_customreg', $a);
        } else {
            $subject = get_string('email_approved_subject', 'local_customreg', $a);
            $body = get_string('email_approved_body', 'local_customreg', $a);
        }
    } else {
        $a->uploadurl = new moodle_url('/local/customreg/upload.php');
        $subject = get_string('email_rejected_subject', 'local_customreg', $a);
        $body = get_string('email_rejected_body', 'local_customreg', $a);
    }

    email_to_user($user, core_user::get_noreply_user(), $subject, $body);
}

/**
 * Notify user about individual course approval
 */
function local_customreg_notify_course_approved($userid, $courseid, $comments = '') {
    global $DB, $CFG;

    $user = $DB->get_record('user', ['id' => $userid], '*', MUST_EXIST);
    $course = $DB->get_record('course', ['id' => $courseid], '*', MUST_EXIST);

    $a = new stdClass();
    $a->firstname = $user->firstname;
    $a->coursename = $course->fullname;
    $a->comments = !empty($comments) ? $comments : get_string('notapplicable', 'local_customreg');
    $a->courseurl = new moodle_url('/course/view.php', ['id' => $courseid]);
    $a->sitelink = $CFG->wwwroot;

    $subject = get_string('email_course_approved_subject', 'local_customreg', $a);
    $body = get_string('email_course_approved_body', 'local_customreg', $a);

    email_to_user($user, core_user::get_noreply_user(), $subject, $body);
}

/**
 * Legacy hook for after signup
 */
function local_customreg_after_signup($user, $data) {
    \local_customreg\hook_handler::after_signup_legacy($user, $data);
}

/**
 * Serve files for this plugin
 */
function local_customreg_pluginfile($course, $cm, $context, $filearea, $args, $forcedownload, array $options = []) {
    global $DB;

    if ($context->contextlevel != CONTEXT_SYSTEM) {
        return false;
    }

    require_login();

    if ($filearea !== 'govid') {
        return false;
    }

    $userid = (int)array_shift($args);
    $filename = array_pop($args);

    // Only owners or admins can see the file
    if ($userid != $GLOBALS['USER']->id && !is_siteadmin()) {
        send_file_not_found();
    }

    $fs = get_file_storage();
    $file = $fs->get_file($context->id, 'local_customreg', 'govid', $userid, '/', $filename);

    if (!$file) {
        send_file_not_found();
    }

    send_stored_file($file, 0, 0, $forcedownload, $options);
}

/**
 * Helper to enroll a user into a course manually
 */
function local_customreg_enroll_user_into_course($userid, $courseid) {
    global $DB;
    
    $course = $DB->get_record('course', ['id' => $courseid]);
    if (!$course) return false;

    // Get the manual enrol plugin
    $manualenrol = enrol_get_plugin('manual');
    if (!$manualenrol) return false;

    // Find the manual instance in this course
    $instances = enrol_get_instances($courseid, true);
    $manualinstance = null;
    foreach ($instances as $instance) {
        if ($instance->enrol == 'manual') {
            $manualinstance = $instance;
            break;
        }
    }
    
    // Fallback: If no manual instance, add one
    if (!$manualinstance) {
        $manualinstanceid = $manualenrol->add_default_instance($course);
        $manualinstance = $DB->get_record('enrol', ['id' => $manualinstanceid]);
    }
    
    // Check if already enrolled
    if (is_enrolled(context_course::instance($courseid), $userid)) {
        return 'alreadyenrolled';
    }
    
    // Assign Student Role ( обычно roleid = 5 по умолчанию )
    $roleid = $DB->get_field('role', 'id', ['shortname' => 'student']);
    $manualenrol->enrol_user($manualinstance, $userid, $roleid);
    return 'enrollsuccess';
}

/**
 * Validate that no more than 5 courses are selected in the admin settings.
 */
function local_customreg_validate_courses($value) {
    if (empty($value)) {
        return true; 
    }
    
    // Config values are often CSV strings from multiselects
    if (!is_array($value)) {
        $value = explode(',', $value);
    }
    
    // Filter out zeros or empty values
    $value = array_filter($value, function($v) {
        return !empty($v) && $v != '0';
    });
    
    if (count($value) > 5) {
        return get_string('maxcoursesreached', 'local_customreg');
    }
    return true;
}

/**
 * Export registration data in multiple formats
 * @param array $records Array of registration records
 * @param string $format Export format: 'csv' | 'tsv' | 'ods' | 'html'
 * @return array Array with 'headers' and 'data' keys for export
 */
function local_customreg_export_table_data($records, $format = 'csv') {
    global $DB;
    
    $headers = [
        'User ID',
        get_string('csv_firstname', 'local_customreg'),
        get_string('csv_lastname', 'local_customreg'),
        get_string('csv_email', 'local_customreg'),
        get_string('csv_studentid', 'local_customreg', 'Student ID'),
        get_string('csv_status', 'local_customreg'),
        get_string('csv_courses', 'local_customreg'),
        get_string('csv_timecreated', 'local_customreg')
    ];
    
    $data = [];
    
    foreach ($records as $rec) {
        // Format course list for export
        $coursesjson = json_decode($rec->courseidsjson, true) ?: [];
        $courselist = [];
        foreach ($coursesjson as $cinfo) {
            $course = $DB->get_record('course', ['id' => $cinfo['id']], 'shortname');
            if ($course) {
                $courselist[] = "{$course->shortname} ({$cinfo['status']})";
            }
        }
        
        $data[] = [
            $rec->userid,
            $rec->firstname,
            $rec->lastname,
            $rec->email,
            $rec->institutionid,
            $rec->status,
            implode(' | ', $courselist),
            userdate($rec->timecreated, '%Y-%m-%d %H:%M:%S')
        ];
    }
    
    return [
        'headers' => $headers,
        'data' => $data
    ];
}

/**
 * Legacy hook for enforcement
 */
function local_customreg_before_http_headers() {
    \local_customreg\hook_handler::before_http_headers_logic();
}

