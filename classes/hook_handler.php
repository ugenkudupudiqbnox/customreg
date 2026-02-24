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
        global $USER, $DB, $PAGE, $CFG;

        if (!isloggedin() || isguestuser()) {
            return;
        }

        // Only enforce for students (Skip admins, managers, and course creators)
        $systemcontext = context_system::instance();
        if (is_siteadmin() || 
            has_capability('moodle/site:config', $systemcontext) ||
            has_capability('moodle/course:create', $systemcontext) ||
            has_capability('moodle/user:create', $systemcontext)) {
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
                'courseidsjson' => json_encode([]),
                'timecreated' => time(),
                'timemodified' => time()
            ];
            
            $rec->id = $DB->insert_record('local_customreg', $rec);
            
            // Log the initial record creation
            require_once($CFG->dirroot . '/local/customreg/lib.php');
            local_customreg_log($USER->id, 'raised', 'Initial registration record created via enforcement hook (slipped through signup).');

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

        error_log("CUSTOMREG: after_signup_legacy for UID: " . $user->id);

        $rec = $DB->get_record('local_customreg', ['userid' => $user->id]);
        if (!$rec) {
            $rec = (object)[
                'userid' => $user->id,
                'identitytype' => 'new',
                'institutionid' => '',
                'courseidsjson' => json_encode([]),
                'documentrequired' => 1,
                'documentuploaded' => 0,
                'status' => 'pending',
                'timecreated' => time(),
                'timemodified' => time()
            ];
            $DB->insert_record('local_customreg', $rec);
        }

        require_once($CFG->dirroot . '/local/customreg/lib.php');
        local_customreg_log($user->id, 'raised', 'Initial registration record created. Data will be collected after login.');
    }

    /**
     * Extend signup form with identity fields (NOW REMOVED - MOVED TO AFTER LOGIN)
     */
    public static function signup_form_definition(\core\hook\user\signup_form_definition $hook): void {
        // No longer extending the signup form as Moodle strips the data.
        // All fields are now gathered on the upload.php page after user registration.
    }

    /**
     * Signup form validation logic (NOW REMOVED)
     */
    public static function signup_form_validation_callback($data, $files, $mform) {
        return [];
    }
}
