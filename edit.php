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
 *
 * @package   mod_dialogue {@link https://docs.moodle.org/dev/Frankenstyle}
 * @copyright 2019 LearningWorks Ltd {@link http://www.learningworks.co.nz}
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

require_once(__DIR__ . '/../../config.php');

$id = required_param('id', PARAM_INT);
if ($id) {
    var_dump($id);
    $conversation = new \mod_dialogue\local\persistent\conversation_persistent($id);
} else {
    $cmid = required_param('cmid', PARAM_INT);
    $conversation = new \mod_dialogue\local\persistent\conversation_persistent();
}
//var_dump($conversation->to_record());die;
$cm = get_coursemodule_from_id('dialogue', $cmid, 0, false, MUST_EXIST);
$dialogue = new \mod_dialogue\local\persistent\dialogue_persistent($cm->instance);
$course = $dialogue->get('course');
$context = $dialogue->get_context();
$PAGE->set_cm($cm, $course, $dialogue->to_record());
$PAGE->set_context($context);
$pageurl = new moodle_url('/mod/dialogue/edit.php');
$PAGE->set_url($pageurl);
$PAGE->requires->css('/mod/dialogue/recipient.css');
$customdata['dialogue'] = $dialogue;
$customdata['persistent'] = $conversation;
$form = new mod_dialogue\local\form\conversation_form(null, $customdata);
if ($data = $form->get_data()) {
    print_object($data);
}
$renderer = $PAGE->get_renderer('mod_dialogue');
echo $OUTPUT->header();
//echo $renderer->render_from_template('mod_dialogue/recipient_drawer', []);
echo $form->render();

//$form->display();
echo $OUTPUT->footer($course);
