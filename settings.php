<?php
defined('MOODLE_INTERNAL') || die();

if ($hassiteconfig) {
    $ADMIN->add('localplugins', new admin_externalpage(
        'local_customreg_manage',
        get_string('manageusers', 'local_customreg'),
        new moodle_url('/local/customreg/manage.php'),
        'local/customreg:manage'
    ));
}
