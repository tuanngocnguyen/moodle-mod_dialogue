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

defined('MOODLE_INTERNAL') || die();

/**
 * A custom renderer class that extends the plugin_renderer_base and is used by
 * the dialogue module.
 *
 * @package   mod_dialogue
 * @copyright 2013 Troy Williams
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class mod_dialogue_renderer extends plugin_renderer_base {

    /**
     * Render conversation, just the conversation
     *
     * @global type $PAGE
     * @global type $OUTPUT
     * @global type $USER
     * @param dialogue_conversation $conversation
     * @return string
     */
    public function render_conversation(mod_dialogue\conversation $conversation) {
        global $PAGE, $OUTPUT, $USER;

        $context = $conversation->dialogue->context; // fetch context from parent dialogue
        $cm      = $conversation->dialogue->cm; // fetch course module from parent dialogue

        $today    = strtotime("today");
        $yearago  = strtotime("-1 year");

        $html = '';

        $html .= html_writer::start_div('conversation-heading');
        $html .= html_writer::tag('h3', $conversation->subject, array('class' => 'heading'));

        if ($conversation->state == \mod_dialogue\dialogue::STATE_OPEN) {
            $span = html_writer::tag('span', get_string('open', 'dialogue'), array('class' => "state-indicator state-open"));
            $html .= html_writer::tag('h3', $span, array('class' => 'heading pull-right'));
        }

        if ($conversation->state == \mod_dialogue\dialogue::STATE_CLOSED) {
            $span = html_writer::tag('span', get_string('closed', 'dialogue'), array('class' => "state-indicator state-closed"));
            $html .= html_writer::tag('h3', $span, array('class' => 'heading pull-right'));
        }

        if ($conversation->state == \mod_dialogue\dialogue::STATE_BULK_AUTOMATED) {
            $span = html_writer::tag('span', get_string('bulkopener', 'dialogue'), array('class' => "state-indicator state-bulk"));
            $html .= html_writer::tag('h3', $span, array('class' => 'heading pull-right'));
        }

        $html .= html_writer::end_div(); // close header

        $html .= html_writer::start_div('conversation');
        $messageid = 'm' . $conversation->messageid;

        $html .= html_writer::tag('a', '', array('id' => $messageid));
        $avatar = $OUTPUT->user_picture($conversation->author, array('size' => true, 'class' => 'userpicture img-rounded'));

        $html .= html_writer::div($avatar, 'conversation-object pull-left');

        $html .= html_writer::start_div('conversation-body');

        $datestrings = (object) dialogue_get_humanfriendly_dates($conversation->timemodified);
        $datestrings->fullname = fullname($conversation->author); //sneaky
        if ($conversation->timemodified >= $today) {
            $openedbyheader = get_string('openedbytoday', 'dialogue', $datestrings);
        } else if ($conversation->timemodified >= $yearago) {
            $openedbyheader = get_string('openedbyshortyear', 'dialogue', $datestrings);
        } else {
            $openedbyheader = get_string('openedbyfullyear', 'dialogue', $datestrings);
        }

        $html .= html_writer::start_div('conversation-header');
        $html .= html_writer::tag('span', $openedbyheader, array('class' => 'conversation-openedby pull-left'));

        $html .= html_writer::start_tag('ul', array('class' => "message-actions pull-right"));

        if ($conversation->state == \mod_dialogue\dialogue::STATE_OPEN) {
            $canclose = ((has_capability('mod/dialogue:close', $context) and $USER->id == $conversation->author->id) or
                          has_capability('mod/dialogue:closeany', $context));


            if ($canclose) {
                $lockicon = html_writer::tag('i', '', array('class' => "fa fa-lock"));
                $html .= html_writer::start_tag('li');
                $closeurl = new moodle_url('/mod/dialogue/conversation.php');
                $closeurl->param('id', $cm->id);
                $closeurl->param('conversationid', $conversation->conversationid);
                $closeurl->param('action', 'close');
                $html .= html_writer::link($closeurl,  get_string('closeconversation', 'dialogue') . $lockicon);
                $html .= html_writer::end_tag('li');
            }
        }

        $candelete = ((has_capability('mod/dialogue:delete', $context) and $USER->id == $conversation->author->id) or
                       has_capability('mod/dialogue:deleteany', $context));

        if ($candelete) {
            $html .= html_writer::start_tag('li');
            $trashicon = html_writer::tag('i', '', array('class' => "fa fa-trash-o"));
            $deleteurl = new moodle_url('/mod/dialogue/conversation.php');
            $deleteurl->param('id', $cm->id);
            $deleteurl->param('conversationid', $conversation->conversationid);
            $deleteurl->param('action', 'delete');
            $html .= html_writer::link($deleteurl,  get_string('deleteconversation', 'dialogue') . $trashicon);
            $html .= html_writer::end_tag('li');
        }

        $html .= html_writer::end_tag('ul');
        $html .= html_writer::empty_tag('br');
        $html .= html_writer::end_div();

        $html .= html_writer::empty_tag('hr');
        $html .= $conversation->bodyhtml;
        $html .= $this->render_attachments($conversation->attachments);
        $html .= html_writer::end_div();
        //$html .= html_writer::end_div();

        // Display list of people who have received this conversation.
        // @todo - display rest of information, which group, has completed? etc
        if ($conversation->state == \mod_dialogue\dialogue::STATE_BULK_AUTOMATED) {
            $receivers = $conversation->receivedby;
            if ($receivers) {
                $html .= html_writer::start_div('participants receivedby');
                $count = count($receivers);
                if ($count == 1) {
                    $openedwithstring = get_string('conversationopenedwith', 'dialogue');
                } else {
                    $openedwithstring = get_string('conversationsopenedwith', 'dialogue', $count);
                }
                $html .= html_writer::span($openedwithstring);
                $html .= html_writer::start_tag('table', array('class'=>'table')); //table-condensed
                $html .= html_writer::start_tag('tbody');
                $sentonstring = new lang_string('senton', 'dialogue');
                foreach ($receivers as $receivedby) {
                    $person = dialogue_get_user_details($conversation->dialogue, $receivedby->userid);
                    $html .= html_writer::start_tag('tr');
                    $picture = $OUTPUT->user_picture($person, array('class' => 'userpicture img-rounded', 'size' => 20));
                    $html .= html_writer::tag('td', $picture);
                    $html .= html_writer::tag('td', fullname($person));
                    $html .= html_writer::tag('td', $sentonstring . userdate($receivedby->timemodified));
                    $html .= html_writer::end_tag('tr');
                }
                $html .= html_writer::end_tag('tbody');
                $html .= html_writer::end_tag('table');
                $html .= html_writer::end_div();
            }
        }
        // This should only display on open and closed conversations @todo - tidy + css
        $participants = $conversation->participants;
        if ($participants) {
            $html .= html_writer::start_div('participants');
            $html .= html_writer::tag('strong', count($participants));
            $html .= '&nbsp;' . get_string('participants', 'dialogue');
            foreach ($participants as $participant) {
                $picture = $OUTPUT->user_picture($participant, array('class' => 'userpicture img-rounded', 'size' => 20));
                $html .= html_writer::tag('span', $picture . '&nbsp;' . fullname($participant), array('class' => 'participant'));
            }
            $html .= html_writer::end_div();
        }
        $html .= html_writer::end_div(); // end of main conversation
        $html .= html_writer::empty_tag('hr');

        return $html;
    }
    /**
     * Builds and returns HTML needed to render the sort by drop down for conversation
     * lists.
     *
     * @global stdClass $PAGE
     * @global stdClass $OUTPUT
     * @param array $options
     * @param string $sort
     * @param string $direction
     * @return string $html
     * @throws moodle_exception
     */
    public function list_sortby($options, $sort, $direction) {
        global $PAGE, $OUTPUT;

        $html = '';
        $nonjsoptions = array();

        if (!in_array($sort, array_keys($options))) {
            throw new moodle_exception("Not a sort option");
        }


        $pageurl = clone($PAGE->url);
        $PAGE->url->param('page', 0); // reset pagination

        $html .= html_writer::start_div('dropdown-group pull-right'); //
        $html .= html_writer::start_div('js-control btn-group pull-right');

        $html .= html_writer::start_tag('button', array('data-toggle' => 'dropdown',
                                                        'class' =>'btn btn-small dropdown-toggle'));

        $html .= get_string('sortedby', 'dialogue', get_string($sort, 'dialogue'));
        $html .= html_writer::end_tag('button');
        $html .= html_writer::start_tag('ul', array('class' => 'dropdown-menu'));
        foreach ($options as $option => $settings) {
            $string = get_string($option, 'dialogue');
            $nonjsoptions[$option] = $string;
            if ($settings['directional'] == false) {
                $url = clone($PAGE->url);
                $url->param('sort', $option);
                $html .= html_writer::start_tag('li');
                $html .= html_writer::link($url, $string);
                $html .= html_writer::end_tag('li');
                continue;
            }
            if ($option == $sort) {
                $sortdirection = ($direction == 'desc') ? 'asc' : 'desc';
            } else {
                $sortdirection = \core_text::strtolower($settings['default']);
            }
            $url = clone($PAGE->url);
            $url->param('sort', $option);
            $url->param('direction', $sortdirection);
            // font awesome icon
            $faclass = "fa fa-sort-{$settings['type']}-{$sortdirection} pull-right";
            $faicon = html_writer::tag('i', '', array('class' => $faclass));
            $html .= html_writer::start_tag('li');
            $html .= html_writer::link($url, $faicon . $string);
            $html .= html_writer::end_tag('li');
        }
        $html .= html_writer::end_tag('ul');
        $html .= html_writer::end_div(); // end of js-control

        // Important: non javascript control must be after javascript control else layout borked in chrome.
        $select = new single_select($pageurl, 'sort', $nonjsoptions, $sort, null, 'orderbyform');
        $select->method = 'post';
        $nonjscontrol = $OUTPUT->render($select);
        $html .= html_writer::div($nonjscontrol, 'nonjs-control');

        $html .= html_writer::end_div(); // end of container
        return $html;

    }

    public function sort_by_dropdown($options) {
        global $PAGE, $OUTPUT;
        $html = '';


        $strings = array();
        foreach ($options as $option) {
            $strings[$option] = get_string($option, 'dialogue');
        }

        $pageurl = clone($PAGE->url);

        $PAGE->url->param('page', 0); // reset pagination

        $sort = $PAGE->url->get_param('sort');
        if (!in_array($sort, $options)) {
            throw new coding_exception('$PAGE sort param is not in options');
        }

        $html .= html_writer::start_div('dropdown-group pull-right'); //
        $html .= html_writer::start_div('js-control btn-group pull-right');

        $html .= html_writer::start_tag('button', array('data-toggle' => 'dropdown',
                                                        'class' =>'btn btn-small dropdown-toggle'));

        $html .= get_string('sortedby', 'dialogue', get_string($sort, 'dialogue'));
        $html .= html_writer::tag('tag', null, array('class' => 'caret'));
        $html .= html_writer::end_tag('button');
        $html .= html_writer::start_tag('ul', array('class' => 'dropdown-menu'));

        foreach ($options as $option) {
            $url = clone($PAGE->url);
            $url->param('sort', $option);
            $html .= html_writer::start_tag('li');
            $html .= html_writer::link($url, ucfirst(get_string($option, 'dialogue')));
            $html .= html_writer::end_tag('li');
        }

        $html .= html_writer::end_tag('ul');
        $html .= html_writer::end_div(); // end of js-control

        // Important: non javascript control must be after javascript control else layout borked in chrome.
        $select = new single_select($pageurl, 'sort', $strings, $sort, null, 'orderbyform');
        $select->method = 'post';
        $nonjscontrol = $OUTPUT->render($select);
        $html .= html_writer::div($nonjscontrol, 'nonjs-control');

        $html .= html_writer::end_div(); // end of container
        return $html;

    }

} // end of renderer class
