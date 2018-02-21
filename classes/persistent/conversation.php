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

class conversation extends persistent {

    const TABLE = 'dialogue_conversations';

    protected static function define_properties() {
        global $CFG, $COURSE;
        return [
            'courseid' => [
                'type' => PARAM_INT,
                'default' => 0,
                'description' => 'Foreign key reference to the course'
            ],
            'dialogueid' => [
                'type' => PARAM_INT,
                'default' => 0,
                'description' => 'Foreign key reference to dialogue'
            ],
            'subject' => [
                'type' => PARAM_RAW,
                'description' => 'Subject'
            ],
            'ownerid' => [
                'type' => PARAM_INT,
                'default' => 0,
                'description' => 'Current owner of conversation'
            ],
            'authorid' => [
                'type' => PARAM_INT,
                'default' => 0,
                'description' => 'Initial author of conversation'
            ],
            'recipentid' => [
                'type' => PARAM_INT,
                'default' => 0,
                'description' => 'The recipent of the initated conversation'
            ],
            'body' => array(
                'type' => PARAM_RAW,
                'description' => 'Lesson introduction text.',
                'optional' => true,
            ),
            'bodyformat' => array(
                'choices' => array(FORMAT_HTML, FORMAT_MOODLE, FORMAT_PLAIN, FORMAT_MARKDOWN),
                'type' => PARAM_INT,
                'default' => FORMAT_MOODLE
            ),
            'bodytrust' => [
                'type' => PARAM_INT,
                'default' => 0,
            ],
            'attachments' => [
                'type' => PARAM_INT,
                'default' => 0,
            ],
            'draft' => [
                'type' => PARAM_INT,
                'default' => 1,
            ],
            'closed' => [
                'type' => PARAM_INT,
                'default' => 0,
            ],
            'automated' => [
                'type' => PARAM_INT,
                'default' => 0,
            ],
        ];
    }
}
