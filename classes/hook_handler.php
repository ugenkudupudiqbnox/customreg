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
     * Common logic for enforcement
     */
    public static function before_http_headers_logic(): void {
        global $USER, $DB, $PAGE;

        if (!isloggedin() || isguestuser()) {
            return;
        }

        // Only enforce for regular users (let admins through)
        if (is_siteadmin()) {
            return;
        }

        if (CLI_SCRIPT || (defined('AJAX_SCRIPT') && AJAX_SCRIPT)) {
            return;
        }

        $rec = $DB->get_record('local_customreg', ['userid' => $USER->id]);
        if (!$rec) {
            // Auto-create record for users who slipped through signup audit (assuming 'new')
            $rec = (object)[
                'userid' => $USER->id,
                'identitytype' => 'new',
                'documentrequired' => 1,
                'documentuploaded' => 0,
                'status' => 'pending',
                'timecreated' => time(),
                'timemodified' => time()
            ];
            
            $rec->id = $DB->insert_record('local_customreg', $rec);
            
            // Log the initial record creation
            require_once($CFG->dirroot . '/local/customreg/lib.php');
            local_customreg_log($USER->id, 'raised', 'Initial registration record created via enforcement hook.');

            // Refresh record to ensure it has all defaults if any
            $rec = $DB->get_record('local_customreg', ['id' => $rec->id]);
        }

        $path = $PAGE->url->get_path();

        // Allow upload.php, login process and logout
        if (strpos($path, '/local/customreg/upload.php') !== false || 
            strpos($path, '/login/') !== false || 
            strpos($path, '/logout.php') !== false) {
            return;
        }

        if ($rec->documentrequired == 1) {
            if ($rec->status !== 'approved') {
                // If not approved, force them to stay on upload.php
                // (which will handle the "form" vs "pending review" UI)
                if (strpos($path, '/local/customreg/upload.php') === false) {
                    redirect(new moodle_url('/local/customreg/upload.php'));
                }
                return;
            }
        }
    }

    /**
     * Enforce document status before headers are sent (MODERN)
     */
    public static function before_http_headers(\core\hook\output\before_http_headers $hook): void {
        self::before_http_headers_logic();
    }

    /**
     * Handle signup audit and document requirements
     */
    public static function after_signup(\core\hook\user\after_signup $hook): void {
        self::after_signup_legacy($hook->get_user(), $hook->get_data());
    }

    /**
     * Legacy compatible after signup implementation
     */
    public static function after_signup_legacy($user, $data): void {
        global $DB, $CFG;

        $identitytype = $data->local_customreg_identitytype ?? 'new';
        $isnew = ($identitytype === 'new');

        $rec = (object)[
            'userid' => $user->id,
            'identitytype' => $identitytype,
            'institutionid' => $data->local_customreg_institutionid ?? null,
            'documentrequired' => $isnew ? 1 : 0,
            'documentuploaded' => $isnew ? 0 : 1,
            'status' => $isnew ? 'pending' : 'approved',
            'timecreated' => time(),
            'timemodified' => time()
        ];

        $DB->insert_record('local_customreg', $rec);

        require_once($CFG->dirroot . '/local/customreg/lib.php');
        local_customreg_log($user->id, 'raised', 'Registration raised via signup form.');
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
