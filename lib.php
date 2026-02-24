<?php
defined('MOODLE_INTERNAL') || die();

/**
 * Legacy hook for signup extension
 */
function local_customreg_extend_signup_form($mform) {
    \local_customreg\hook_handler::signup_form_definition_legacy($mform);
}

/**
 * Legacy hook for after signup
 */
function local_customreg_after_signup($user, $data) {
    global $DB;

    // Log the event to standard error log
    error_log("DEBUG: local_customreg_after_signup for USER: " . ($user->id ?? 'NOTSET'));

    $identitytype = $data->local_customreg_identitytype ?? 'new';
    $isnew = ($identitytype === 'new');

    $DB->insert_record('local_customreg', (object)[
        'userid' => $user->id,
        'identitytype' => $identitytype,
        'institutionid' => $data->local_customreg_institutionid ?? null,
        'documentrequired' => $isnew ? 1 : 0,
        'documentuploaded' => $isnew ? 0 : 1,
        'status' => $isnew ? 'pending' : 'approved',
        'timecreated' => time(),
        'timemodified' => time()
    ]);
}

/**
 * Legacy hook for enforcement
 */
function local_customreg_before_http_headers() {
    global $USER, $DB, $PAGE;

    if (!isloggedin() || isguestuser() || is_siteadmin()) {
        return;
    }

    if (CLI_SCRIPT || (defined('AJAX_SCRIPT') && AJAX_SCRIPT)) {
        return;
    }

    $rec = $DB->get_record('local_customreg', ['userid' => $USER->id]);
    if (!$rec) {
        return;
    }

    $path = $PAGE->url->get_path();

    if (strpos($path, '/local/customreg/upload.php') !== false) {
        return;
    }

    if ($rec->documentrequired == 1 && $rec->documentuploaded == 0) {
        redirect(new moodle_url('/local/customreg/upload.php'));
    }

    if ($rec->documentrequired == 1 && $rec->status !== 'approved') {
        print_error(get_string('pendingapproval','local_customreg'));
    }
}

