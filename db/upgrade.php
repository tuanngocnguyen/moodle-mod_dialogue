<?php
// This file is part of Moodle - http://moodle.org/
//
// Moodle is free software: you can redistribute it and/or modify
// it under the terms of the GNU General Public License as published by
// the Free Software Foundation, either version 3 of the License, or
// (at your option) any later version.
//
// Moodle is distributed in the hope that it will be useful,
// but WITHOUT ANY WARRANTY; without even the implied warranty of
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
// GNU General Public License for more details.
//
// You should have received a copy of the GNU General Public License
// along with Moodle.  If not, see <http://www.gnu.org/licenses/>.

function xmldb_dialogue_upgrade($oldversion = 0) {
    global $CFG, $DB, $OUTPUT;

    $dbman = $DB->get_manager(); // Loads ddl manager and xmldb classes.

    /**
     * Moodle v3.4.0 release upgrade line.
     */

    if ($oldversion < 2017111301) {
        // Get dialogue table.
        $table = new xmldb_table('dialogue');

        // Conditionally add field mode.
        $modefield = new xmldb_field('mode', XMLDB_TYPE_INTEGER, '1', null, XMLDB_NOTNULL, null, '0');
        if (!$dbman->field_exists($table, $modefield)) {
            $dbman->add_field($table, $modefield);
        }

        // Conditionally add field creatorroles.
        $creatorrolesfield = new xmldb_field('creatorroles', XMLDB_TYPE_CHAR, '512', null, null, null, null);
        if (!$dbman->field_exists($table,  $creatorrolesfield)) {
            $dbman->add_field($table,  $creatorrolesfield);
        }

        // Conditionally add field receiverroles.
        $receiverrolesfield = new xmldb_field('receiverroles', XMLDB_TYPE_CHAR, '512', null, null, null, null);
        if (!$dbman->field_exists($table, $receiverrolesfield)) {
            $dbman->add_field($table, $receiverrolesfield);
        }

        // Conditionally add field usermodified.
        $usermodifiedfield = new xmldb_field('usermodified', XMLDB_TYPE_INTEGER, '10', null, XMLDB_NOTNULL, null, '0');
        if (!$dbman->field_exists($table, $usermodifiedfield)) {
            $dbman->add_field($table, $usermodifiedfield);
        }

        // Conditionally add field timecreated.
        $timecreatedfield = new xmldb_field('timecreated', XMLDB_TYPE_INTEGER, '10', null, XMLDB_NOTNULL, null, '0');
        if (!$dbman->field_exists($table, $timecreatedfield)) {
            $dbman->add_field($table, $timecreatedfield);
        }

        // TODO - Migrate dialogue data.

        // Conditionally drop field multipleconversations.
        $multipleconversationsfield = new xmldb_field('multipleconversations');
        if ($dbman->field_exists($table, $multipleconversationsfield)) {
            $dbman->drop_field($table, $multipleconversationsfield);
        }

        // Conditionally drop field notifications.
        $notificationsfield = new xmldb_field('notifications');
        if ($dbman->field_exists($table, $notificationsfield)) {
            $dbman->drop_field($table, $notificationsfield);
        }

        // Conditionally drop field notificationcontent.
        $notificationcontentfield = new xmldb_field('notificationcontent');
        if ($dbman->field_exists($table, $notificationcontentfield)) {
            $dbman->drop_field($table, $notificationcontentfield);
        }

        // Conditionally add field usermodified.
        $usermodifiedfield = new xmldb_field('usermodified', XMLDB_TYPE_INTEGER, '10', null, XMLDB_NOTNULL, null, '0');
        if (!$dbman->field_exists($table, $usermodifiedfield)) {
            $dbman->add_field($table, $usermodifiedfield);
        }

        // Conditionally add field timecreated.
        $timecreatedfield = new xmldb_field('timecreated', XMLDB_TYPE_INTEGER, '10', null, XMLDB_NOTNULL, null, '0');
        if (!$dbman->field_exists($table, $timecreatedfield)) {
            $dbman->add_field($table, $timecreatedfield);
        }

        // Get conversations table.
        $table = new xmldb_table('dialogue_conversations');

        // Conditionally add field ownerid.
        $owneridfield = new xmldb_field('ownerid', XMLDB_TYPE_INTEGER, '10', null, XMLDB_NOTNULL, null, '0');
        if (!$dbman->field_exists($table, $owneridfield)) {
            $dbman->add_field($table, $owneridfield);
        }

        // Conditionally add field authorid.
        $authoridfield = new xmldb_field('authorid', XMLDB_TYPE_INTEGER, '10', null, XMLDB_NOTNULL, null, '0');
        if (!$dbman->field_exists($table, $authoridfield)) {
            $dbman->add_field($table, $authoridfield);
        }

        // Conditionally add field recipientid.
        $owneridfield = new xmldb_field('recipientid', XMLDB_TYPE_INTEGER, '10', null, XMLDB_NOTNULL, null, '0');
        if (!$dbman->field_exists($table, $owneridfield)) {
            $dbman->add_field($table, $owneridfield);
        }

        // Conditionally add field state.
        $statefield = new xmldb_field('state', XMLDB_TYPE_INTEGER, '1', null, XMLDB_NOTNULL, null, '0');
        if (!$dbman->field_exists($table, $statefield)) {
            $dbman->add_field($table, $statefield);
        }

        // Conditionally add field automated.
        $automatedfield = new xmldb_field('automated', XMLDB_TYPE_INTEGER, '1', null, XMLDB_NOTNULL, null, '0');
        if (!$dbman->field_exists($table, $automatedfield)) {
            $dbman->add_field($table, $automatedfield);
        }

        // Conditionally add field usermodified.
        $usermodifiedfield = new xmldb_field('usermodified', XMLDB_TYPE_INTEGER, '10', null, XMLDB_NOTNULL, null, '0');
        if (!$dbman->field_exists($table, $usermodifiedfield)) {
            $dbman->add_field($table, $usermodifiedfield);
        }

        // Conditionally add field timecreated.
        $timecreatedfield = new xmldb_field('timecreated', XMLDB_TYPE_INTEGER, '10', null, XMLDB_NOTNULL, null, '0');
        if (!$dbman->field_exists($table, $timecreatedfield)) {
            $dbman->add_field($table, $timecreatedfield);
        }

        // Conditionally add field timemodified.
        $timemodifiedfield = new xmldb_field('timemodified', XMLDB_TYPE_INTEGER, '10', null, XMLDB_NOTNULL, null, '0');
        if (!$dbman->field_exists($table, $timemodifiedfield)) {
            $dbman->add_field($table, $timemodifiedfield);
        }

        // TODO - Migrate conversation data.

        // Conditionally rename field course to courseid.
        $coursefield = new xmldb_field('course', XMLDB_TYPE_INTEGER, '10', null, XMLDB_NOTNULL, null, 0);
        if (!$dbman->field_exists($table, $coursefield)) {
            $dbman->rename_field($table, $coursefield, 'courseid');
        }

        // Get messages table.
        $table = new xmldb_table('dialogue_messages');

        // Conditionally add field draft.
        $draftfield = new xmldb_field('draft', XMLDB_TYPE_INTEGER, '1', null, XMLDB_NOTNULL, null, '1');
        if (!$dbman->field_exists($table, $draftfield)) {
            $dbman->add_field($table, $draftfield);
        }

        // Conditionally add field usermodified.
        $usermodifiedfield = new xmldb_field('usermodified', XMLDB_TYPE_INTEGER, '10', null, XMLDB_NOTNULL, null, '0');
        if (!$dbman->field_exists($table, $usermodifiedfield)) {
            $dbman->add_field($table, $usermodifiedfield);
        }

        // Conditionally add field timecreated.
        $timecreatedfield = new xmldb_field('timecreated', XMLDB_TYPE_INTEGER, '10', null, XMLDB_NOTNULL, null, '0');
        if (!$dbman->field_exists($table, $timecreatedfield)) {
            $dbman->add_field($table, $timecreatedfield);
        }

        // Conditionally add field timemodified.
        $timemodifiedfield = new xmldb_field('timemodified', XMLDB_TYPE_INTEGER, '10', null, XMLDB_NOTNULL, null, '0');
        if (!$dbman->field_exists($table, $timemodifiedfield)) {
            $dbman->add_field($table, $timemodifiedfield);
        }

        // TODO - Migrate message data.

        // Conditionally drop field draft.
        $statefield = new xmldb_field('state');
        if ($dbman->field_exists($table, statefield)) {
            $dbman->drop_field($table, $statefield);
        }

        // Dialogue savepoint reached.
        upgrade_mod_savepoint(true, 2017111301, 'dialogue');
    }

    return true;
}
