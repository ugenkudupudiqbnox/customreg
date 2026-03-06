<?php
defined('MOODLE_INTERNAL') || die();

function xmldb_local_customreg_upgrade($oldversion) {
    global $DB;

    $dbman = $DB->get_manager();

    if ($oldversion < 2026022419) {
        $table = new xmldb_table('local_customreg');

        if (!$dbman->table_exists($table)) {
            $table->add_field('id', XMLDB_TYPE_INTEGER, '10', null, XMLDB_NOTNULL, XMLDB_SEQUENCE, null);
            $table->add_field('userid', XMLDB_TYPE_INTEGER, '10', null, XMLDB_NOTNULL, null, '0');
            $table->add_field('identitytype', XMLDB_TYPE_CHAR, '50', null, XMLDB_NOTNULL, null, 'new');
            $table->add_field('institutionid', XMLDB_TYPE_CHAR, '100', null, null, null, null);
            $table->add_field('courseidsjson', XMLDB_TYPE_TEXT, null, null, null, null, null);
            $table->add_field('documentuploaded', XMLDB_TYPE_INTEGER, '1', null, XMLDB_NOTNULL, null, '0');
            $table->add_field('documentrequired', XMLDB_TYPE_INTEGER, '1', null, XMLDB_NOTNULL, null, '0');
            $table->add_field('status', XMLDB_TYPE_CHAR, '50', null, XMLDB_NOTNULL, null, 'pending');
            $table->add_field('verifiedby', XMLDB_TYPE_INTEGER, '10', null, null, null, null);
            $table->add_field('timeverified', XMLDB_TYPE_INTEGER, '10', null, null, null, null);
            $table->add_field('timecreated', XMLDB_TYPE_INTEGER, '10', null, XMLDB_NOTNULL, null, '0');
            $table->add_field('timemodified', XMLDB_TYPE_INTEGER, '10', null, XMLDB_NOTNULL, null, '0');

            $table->add_key('primary', XMLDB_KEY_PRIMARY, array('id'));
            $table->add_index('userid', XMLDB_INDEX_UNIQUE, array('userid'));

            $dbman->create_table($table);
        }

        upgrade_plugin_savepoint(true, 2026022419, 'local', 'customreg');
    }

    if ($oldversion < 2026022432) {
        $table = new xmldb_table('local_customreg_logs');

        if (!$dbman->table_exists($table)) {
            $table->add_field('id', XMLDB_TYPE_INTEGER, '10', null, XMLDB_NOTNULL, XMLDB_SEQUENCE, null);
            $table->add_field('userid', XMLDB_TYPE_INTEGER, '10', null, XMLDB_NOTNULL, null, '0');
            $table->add_field('adminid', XMLDB_TYPE_INTEGER, '10', null, XMLDB_NOTNULL, null, '0');
            $table->add_field('action', XMLDB_TYPE_CHAR, '50', null, XMLDB_NOTNULL, null, null);
            $table->add_field('details', XMLDB_TYPE_TEXT, null, null, null, null, null);
            $table->add_field('timecreated', XMLDB_TYPE_INTEGER, '10', null, XMLDB_NOTNULL, null, '0');

            $table->add_key('primary', XMLDB_KEY_PRIMARY, array('id'));
            $table->add_index('userid', XMLDB_INDEX_NOTUNIQUE, array('userid'));

            $dbman->create_table($table);
        }
        upgrade_plugin_savepoint(true, 2026022432, 'local', 'customreg');
    }

    if ($oldversion < 2026030401) {
        $table = new xmldb_table('local_customreg');
        $field = new xmldb_field('admin_comments', XMLDB_TYPE_TEXT, null, null, null, null, null, 'timemodified');

        if (!$dbman->field_exists($table, $field)) {
            $dbman->add_field($table, $field);
        }

        upgrade_plugin_savepoint(true, 2026030401, 'local', 'customreg');
    }

    if ($oldversion < 2026030605) {
        upgrade_plugin_savepoint(true, 2026030605, 'local', 'customreg');
    }

    return true;
}
