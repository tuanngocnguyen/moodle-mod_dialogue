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

namespace mod_dialogue\persistent;

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
                'choices' => array(FORMAT_HTML, FORMAT_MOODLE, FORMAT_PLAIN, FORMAT_MARKDOWN),
                'type' => PARAM_INT,
                'default' => FORMAT_MOODLE
            ),
            'maxattachments' => [
                'type' => PARAM_INT,
                'choices' => function() {
                    return plugin_config::get_property_choices('maxattachments');
                },
            ],
            'maxbytes' => [
                'type' => PARAM_INT,
                'choices' => function() use ($CFG, $COURSE) {
                    $modulebytes = plugin_config::get_property_choices('maxbytes');
                    return get_max_upload_sizes($CFG->maxbytes, $COURSE->maxbytes, $modulebytes);
                },
            ],
            'rolebasedopening' => [
                'type' => PARAM_INT,
                'choices' => [0, 1],
                'default' => 0
            ],
            'openerroles' => [
                'type' => PARAM_RAW,
            ],
            'receiverroles' => [
                'type' => PARAM_RAW,
            ]
        ];
    }
}
