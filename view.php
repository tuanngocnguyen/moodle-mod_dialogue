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

require_once(__dir__ . '/../../config.php');
require_once('lib.php');
require_once('locallib.php');

$cmid = required_param('id', PARAM_INT);
$cm = get_coursemodule_from_id('dialogue', $cmid, 0, false, MUST_EXIST);
$dialogue = new \mod_dialogue\local\persistent\dialogue_persistent($cm->instance);
$course = $dialogue->get('course');
$context = context_module::instance($cm->id);

require_login($course, false, $cm);

$pageurl = new moodle_url('/mod/dialogue/view.php');
$pageurl->param('id', $cm->id);
$PAGE->set_pagetype('mod-dialogue-view-index');
$PAGE->set_cm($cm, $course, $dialogue->to_record());
$PAGE->set_context($context);
$PAGE->set_cacheable(false);
$PAGE->set_url($pageurl);
$PAGE->set_title(format_string($dialogue->get('name')));
$PAGE->set_heading(format_string($course->fullname));

$renderer = $PAGE->get_renderer('mod_dialogue');

echo $OUTPUT->header();
//echo $OUTPUT->heading($activityrecord->name);
//if (!empty($dialogue->activityrecord->intro)) {
    //echo $OUTPUT->box(format_module_intro('dialogue', $dialogue->activityrecord, $cm->id), 'generalbox', 'intro');
//}

//$data = new stdClass(); // new single_button().
//$url = new moodle_url('/mod/dialogue/edit.php');
//$data->id = '-1';
//$data->cmid = $cm->id;
//$data->sesskey = sesskey();
//$data->url = $url->out();
//$data->label = get_string('startanew', 'mod_dialogue');
//echo $renderer->render_new_dialogue_button($data);


$button = new single_button(
    new moodle_url('/mod/dialogue/edit.php', ['id' => '0', 'cmid' => $cm->id]),
    get_string('startanew', 'mod_dialogue'),
    'post',
    true
);


//moodle_url $url, $label, $method='post', $primary=false

echo $renderer->render($button);
echo html_writer::empty_tag('hr');
$tabtree = \mod_dialogue\local\tab_tree::build_navigation();
echo $renderer->render($tabtree);
echo $renderer->render_list_filter_selector();
echo $renderer->render_list_sort_selector();
//echo $renderer->render_list_toolbar();

//$toolbar = new mod_dialogue\output\view_listing_toolbar();
//echo $renderer->render($toolbar);
//echo $renderer->list_sortby(\mod_dialogue\conversations_by_author::get_sort_options(), $sort, $direction);
//echo $renderer->conversation_listing($list);
echo $OUTPUT->footer($course);
