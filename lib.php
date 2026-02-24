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
 * Legacy hook for enforcement
 */
function local_customreg_before_http_headers() {
    \local_customreg\hook_handler::before_http_headers_logic();
}

