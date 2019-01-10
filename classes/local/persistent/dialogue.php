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

namespace mod_dialogue\local\persistent;

use core\persistent;
use mod_dialogue\plugin_config;

defined('MOODLE_INTERNAL') || die();

class dialogue extends persistent {

    const TABLE = 'dialogue';

    protected static function define_properties() {
        global $CFG, $COURSE;
        return [
            'course' => [
                'type' => PARAM_INT,
                'default' => 0,
                'description' => 'Foreign key reference to the course'
            ],
            'name' => [
                'type' => PARAM_RAW,
                'description' => 'Dialogue name.'
            ],
            'intro' => array(
                'type' => PARAM_RAW,
                'description' => 'Dialogue introduction text.',
                'optional' => true,
            ),
            'introformat' => array(
                'type' => PARAM_INT,
                'choices' => array(FORMAT_HTML, FORMAT_MOODLE, FORMAT_PLAIN, FORMAT_MARKDOWN),
                'default' => FORMAT_MOODLE
            ),
            'maxattachments' => [
                'type' => PARAM_INT,
                'choices' => static::get_max_attachments(),
                'default' => plugin_config::get_property_default('maxattachments'),
            ],
            'maxbytes' => [
                'type' => PARAM_INT,
                'choices' => static::get_max_bytes(),
                'default' => plugin_config::get_property_default('maxbytes')
            ],
            'rolebasedopening' => [
                'type' => PARAM_INT,
                'choices' => [0, 1],
                'default' => 0
            ],
            'openerroles' => [
                'type' => PARAM_RAW,
                'null' => NULL_ALLOWED,
                'default' => ''

            ],
            'receiverroles' => [
                'type' => PARAM_RAW,
                'null' => NULL_ALLOWED,
                'default' => ''
            ]
        ];
    }

    public static function get_max_attachments() {
        $choices = [];
        $maxattachments = plugin_config::get('maxattachments');
        foreach (plugin_config::get_property_choices('maxattachments') as $choice) {
            if ($choice > $maxattachments) {
                break;
            }
            array_push($choices, $choice);
        }
        return $choices;
    }

    public static function get_max_bytes() {
        global $CFG, $COURSE;
        $choices = [];
        $maxmodulebytes = plugin_config::get('maxbytes');
        return array_keys(get_max_upload_sizes($CFG->maxbytes, $COURSE->maxbytes, $maxmodulebytes));
    }
}
