<?php // $Id: mysql.php,v 1.4 2005/07/17 02:51:51 moodler Exp $

function dialogue_upgrade($oldversion) {
// This function does anything necessary to upgrade
// older versions to match current functionality

    global $CFG;

    if ($oldversion < 2003100500) {
        execute_sql(" ALTER TABLE `{$CFG->prefix}dialogue` ADD `multipleconversations` TINYINT(3) UNSIGNED NOT NULL DEFAULT '0' AFTER `dialoguetype`");
        execute_sql(" ALTER TABLE `{$CFG->prefix}dialogue_conversations` ADD `subject` VARCHAR(100) NOT NULL DEFAULT ''");
    }

    if ($oldversion < 2003101300) {
        execute_sql(" ALTER TABLE `{$CFG->prefix}dialogue_conversations` ADD `seenon` INT(10) unsigned NOT NULL DEFAULT '0' AFTER `closed`");
    }
    
    if ($oldversion < 2004111000) {
        execute_sql("ALTER TABLE {$CFG->prefix}dialogue DROP KEY course;",false);
        execute_sql("ALTER TABLE {$CFG->prefix}dialogue_conversations DROP KEY recipientid;",false);
        execute_sql("ALTER TABLE {$CFG->prefix}dialogue_conversations DROP KEY userid;",false);
        execute_sql("ALTER TABLE {$CFG->prefix}dialogue_entries DROP KEY dialogueid;",false);
        execute_sql("ALTER TABLE {$CFG->prefix}dialogue_entries DROP KEY userid;",false);

        modify_database('','ALTER TABLE prefix_dialogue ADD KEY course (course);');
        modify_database('','ALTER TABLE prefix_dialogue_conversations ADD KEY recipientid (recipientid);');
        modify_database('','ALTER TABLE prefix_dialogue_conversations ADD KEY userid (userid);');
        modify_database('','ALTER TABLE prefix_dialogue_entries ADD KEY dialogueid (dialogueid);');
        modify_database('','ALTER TABLE prefix_dialogue_entries ADD KEY userid (userid);');
    }
    
    $result = true;
    return $result;
}

?>