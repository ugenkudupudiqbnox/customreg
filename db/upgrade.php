<?php
defined('MOODLE_INTERNAL') || die();

function xmldb_local_customreg_upgrade($oldversion) {
    global $DB;

    $dbman = $DB->get_manager();
    $table = new xmldb_table('local_customreg');

    if ($oldversion < 2026022415) {

        $field = new xmldb_field('documentrequired', XMLDB_TYPE_INTEGER, '1', null, XMLDB_NOTNULL, null, '0', 'documentuploaded');

        if (!$dbman->field_exists($table, $field)) {
            $dbman->add_field($table, $field);
        }

        upgrade_plugin_savepoint(true, 2026022415, 'local', 'customreg');
    }

    return true;
}
