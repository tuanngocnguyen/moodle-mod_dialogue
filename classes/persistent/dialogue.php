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

defined('MOODLE_INTERNAL') || die();

/**
 * Library of extra functions for the dialogue module not part of the standard add-on module API set
 * but used by scripts in the mod/dialogue folder
 *
 * @package   mod_dialogue
 * @copyright 2013 Troy Williams
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */


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
                'description' => 'Lesson introduction text.',
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
                    return range(0, 10); // TODO implement plugin  config.
                },
            ],
            'maxbytes' => [
                'type' => PARAM_INT,
                'choices' => function() use ($CFG, $COURSE) {
                    return get_max_upload_sizes($CFG->maxbytes, $COURSE->maxbytes); // TODO implement plugin  config.
                },
            ],
            'usecoursegroups' => [
                'type' => PARAM_INT,
                'choices' => [0, 1],
                'default' => 0
            ],
            'mode' => [
                'type' => PARAM_INT,
                'choices' => [0, 1],
                'default' => 0
            ],
            'creatorroles' => [
                'type' => PARAM_RAW,
            ],
            'receiverroles' => [
                'type' => PARAM_RAW,

            ]
        ];
    }
}
