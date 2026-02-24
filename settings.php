<?php
defined('MOODLE_INTERNAL') || die();

if ($hassiteconfig) {
    // Ensure lib.php is loaded for the validation function
    require_once($CFG->dirroot . '/local/customreg/lib.php');

    // Add the Management page link
    $ADMIN->add('localplugins', new admin_externalpage(
        'local_customreg_manage',
        get_string('manageusers', 'local_customreg'),
        new moodle_url('/local/customreg/manage.php'),
        'local/customreg:manage'
    ));

    // Add settings page for course availability
    $settings = new admin_settingpage('local_customreg_settings', get_string('pluginname', 'local_customreg'));
    $ADMIN->add('localplugins', $settings);

    // List of courses for users to pick (Multi-select)
    require_once($CFG->libdir . '/datalib.php');
    $courses = get_courses('all', 'c.sortorder ASC', 'c.id, c.fullname');
    $options = [0 => get_string('none')];
    foreach ($courses as $c) {
        if ($c->id == SITEID) continue; // Skip front page
        $options[$c->id] = $c->fullname;
    }

    // Add five separate selection controls for courses
    for ($i = 1; $i <= 5; $i++) {
        $setting = new admin_setting_configselect_autocomplete(
            'local_customreg/course' . $i,
            get_string('course' . $i, 'local_customreg'),
            get_string('selectacourse', 'local_customreg'),
            0,
            $options
        );
        $settings->add($setting);
    }
}
