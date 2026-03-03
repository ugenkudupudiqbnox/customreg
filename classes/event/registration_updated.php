<?php
namespace local_customreg\event;

defined('MOODLE_INTERNAL') || die();

/**
 * Event triggered when a registration request is created or changed.
 */
class registration_updated extends \core\event\base {

    /**
     * Init method.
     */
    protected function init() {
        $this->data['crud'] = 'u';
        $this->data['edulevel'] = self::LEVEL_OTHER;
        $this->data['objecttable'] = 'local_customreg';
    }

    /**
     * Returns description of what happened.
     *
     * @return string
     */
    public function get_description() {
        return "The user with id '$this->userid' had their registration status updated to '{$this->other['status']}' by user '{$this->relateduserid}'. Details: {$this->other['details']}";
    }

    /**
     * Return localised event name.
     *
     * @return string
     */
    public static function get_name() {
        return "Registration status updated";
    }

    /**
     * Returns relevant URL.
     *
     * @return \moodle_url
     */
    public function get_url() {
        return new \moodle_url('/local/customreg/manage.php', ['userid' => $this->relateduserid]);
    }
}
