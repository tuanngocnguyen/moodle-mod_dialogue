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

/**
 * Configure site-wide settings specific to the Dialogue modue
 *
 * Note: the only setting currently relates to unread-post tracking - this will only be
 * supported in your courses if you have applied the patch in CONTRIB-1134 which modifies
 * course/lib.php to check and display unread post counts in the course/topic area.
 * If you havent applied that patch this setting will still be stored in Moodle but it
 * will have no effect on the display of your courses, ie users will not see an unread
 * posts count
 *
 * @package mod_dialogue
 * @license http://www.gnu.org/copyleft/gpl.html GNU Public License
 */

defined('MOODLE_INTERNAL') || die();

use mod_dialogue\local\plugin_config;

if ($hassiteconfig) {
    $component = plugin_config::COMPONENT;
    // Display unread count on course homepage.
    $name = "{$component}/trackunread";
    $settings->add(
        new admin_setting_configcheckbox_with_advanced("{$component}/trackunread",
            get_string('configtrackunread', $component),
            '',
            plugin_config::get_property_default('trackunread'))
    );
    // Default total maxbytes of attached files.
    if (isset($CFG->maxbytes)) {
        $settings->add(
            new admin_setting_configselect("{$component}/maxbytes",
                get_string('maxattachmentsize', $component),
                get_string('configmaxbytes', $component),
                plugin_config::get_property_default('maxbytes'),
                plugin_config::get_property_choices('maxbytes')
            )
        );
    }
    // Default number of attachments allowed per post.
    $settings->add(
        new admin_setting_configselect("{$component}/maxattachments",
            get_string('maxattachments', $component),
            get_string('configmaxattachments', $component),
            plugin_config::get_property_default('maxattachments'),
            plugin_config::get_property_choices('maxattachments'))
    );
}
