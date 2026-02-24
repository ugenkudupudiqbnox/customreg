<?php
namespace local_customreg;

use moodle_url;
use context_system;

defined('MOODLE_INTERNAL') || die();

/**
 * Moodle 5.x Hook Handlers for local_customreg
 */
class hook_handler {

    /**
     * Enforce document status before headers are sent
     */
    public static function before_http_headers(\core\hook\output\before_http_headers $hook): void {
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

        // Allow upload.php and standard assets
        if (strpos($path, '/local/customreg/upload.php') !== false) {
            return;
        }

        if ($rec->documentrequired == 1) {
            if ($rec->documentuploaded == 0) {
                redirect(new moodle_url('/local/customreg/upload.php'));
            }

            if ($rec->status !== 'approved') {
                throw new \moodle_exception('pendingapproval', 'local_customreg');
            }
        }
    }

        // Allow upload.php and standard assets
        if (strpos($path, '/local/customreg/upload.php') !== false) {
            return;
        }

        if ($rec->documentrequired == 1) {
            if ($rec->documentuploaded == 0) {
                redirect(new moodle_url('/local/customreg/upload.php'));
            }

            if ($rec->status !== 'approved') {
                throw new \moodle_exception('pendingapproval', 'local_customreg');
            }
        }
    }

    /**
     * Handle signup audit and document requirements
     */
    public static function after_signup(\core\hook\user\after_signup $hook): void {
        global $DB;

        $user = $hook->get_user();
        $data = $hook->get_data();

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
     * Legacy compatible signup form extension
     */
    public static function signup_form_definition_legacy(\MoodleQuickForm $mform): void {
        $mform->addElement('select', 'local_customreg_identitytype',
            get_string('areyouexisting', 'local_customreg'),
            [
                'existing' => get_string('existingmember', 'local_customreg'),
                'new' => get_string('newmember', 'local_customreg')
            ]
        );

        $mform->addElement('text', 'local_customreg_institutionid', 
            get_string('institutionid', 'local_customreg'));
        $mform->setType('local_customreg_institutionid', PARAM_ALPHANUMEXT);
    }

    /**
     * Extend signup form with identity fields
     */
    public static function signup_form_definition(\core\hook\user\signup_form_definition $hook): void {
        self::signup_form_definition_legacy($hook->get_mform());
    }
}
