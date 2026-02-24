<?php
namespace local_customreg;

defined('MOODLE_INTERNAL') || die();

/**
 * Event Observers for local_customreg
 */
class observer {
    /**
     * Cleanup plugin tables when a user is deleted from Moodle
     */
    public static function user_deleted(\core\event\user_deleted $event) {
        global $DB;
        $userid = $event->objectid;
        
        if ($userid > 0) {
            $DB->delete_records('local_customreg', ['userid' => $userid]);
            $DB->delete_records('local_customreg_logs', ['userid' => $userid]);
        }
    }
}
