<?php
defined('MOODLE_INTERNAL') || die();

/**
 * Legacy hook for signup extension
 */
function local_customreg_extend_signup_form($mform) {
    \local_customreg\hook_handler::signup_form_definition_legacy($mform);
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
 * Legacy hook for enforcement
 */
function local_customreg_before_http_headers() {
    \local_customreg\hook_handler::before_http_headers_logic();
}

