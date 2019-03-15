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

namespace mod_dialogue\local\form;

class conversation_form extends \core\form\persistent {

    /**
     * @var $persistentclass Full name of linked persistent class.
     */
    protected static $persistentclass = 'mod_dialogue\\local\\persistent\\conversation_persistent';

    /**
     * Override parent constructor so can register custom MoodleQuickForm element.
     *
     * @param null $action
     * @param null $customdata
     * @param string $method
     * @param string $target
     * @param null $attributes
     * @param bool $editable
     * @param null $ajaxformdata
     * @throws \coding_exception
     */
    public function __construct($action = null,
                                $customdata = null,
                                $method = 'post',
                                $target = '',
                                $attributes = null,
                                $editable = true,
                                $ajaxformdata = null) {
        global $CFG;
        // Need to have repository library and form element loaded for this to work.
        require_once($CFG->dirroot . '/repository/lib.php');
        //require_once($CFG->dirroot . '/mod/dialogue/classes/local/form/recipientpicker.php');
        \MoodleQuickForm::registerElementType(
            'recipientpicker',
            $CFG->dirroot . '/mod/dialogue/classes/local/form/recipientpicker.php',
            'MoodleQuickForm_recipientpicker');
        parent::__construct($action, $customdata, $method, $target, $attributes, $editable, $ajaxformdata);
    }

    public function definition() {
        global $PAGE;
        $cm       = $PAGE->cm;
        $context  = $PAGE->context;
        $form     = $this->_form;
        $dialogue     = $this->_customdata['dialogue'];
        $conversation = $this->get_persistent();
        $maxbytes = $dialogue->get('maxbytes');
        $editoroptions = [
            'collapsed' => true,
            'maxfiles' => EDITOR_UNLIMITED_FILES,
            'maxbytes' => $maxbytes,
            'trusttext'=> true,
            'accepted_types' => '*',
            'return_types'=> FILE_INTERNAL | FILE_EXTERNAL
        ];
        $attachmentoptions = [
            'subdirs' => 0,
            'maxbytes' => $maxbytes,
            'maxfiles' => $dialogue->get('maxattachments'),
            'accepted_types' => '*',
            'return_types' => FILE_INTERNAL,
            'areamaxbytes' => $maxbytes
        ];

        // Why? Because I had to, moodleform fun.
        $form->addElement('header', 'all', get_string('yournewdialogue', 'dialogue'));
        $form->addElement('recipientpicker', 'recipient', get_string('recipient', 'dialogue'));
        $form->addElement('text', 'subject', get_string('subject', 'dialogue'), 'size="100%"');
        $form->setType('subject', PARAM_TEXT);
        $form->addElement(
            'editor',
            'body',
            get_string('message', 'dialogue'),
            null,
            $editoroptions
        );
        $form->setType('body', PARAM_RAW);
        if ($dialogue->get('maxattachments'))  {  //  0 = No attachments at all.
            $form->addElement(
                'filemanager',
                'attachments[itemid]',
                get_string('attachments', 'dialogue'),
                null,
                $attachmentoptions
            );
        }

        $actionbuttongroup = array();
        $actionbuttongroup[] =& $form->createElement('submit', 'send', get_string('send', 'dialogue'));
        $actionbuttongroup[] =& $form->createElement('button', 'save', get_string('savedraft', 'dialogue'));
        $actionbuttongroup[] =& $form->createElement('cancel', 'cancel', get_string('cancel'));
        $actionbuttongroup[] =& $form->createElement('button', 'trash', get_string('trashdraft', 'dialogue'));
        $form->addGroup($actionbuttongroup, 'actionbuttongroup', '', ' ', false);
    }

    public function render() {
        global $PAGE;
        $output = '';
        $renderer = $PAGE->get_renderer('mod_dialogue');
        $output .= $renderer->render_from_template('mod_dialogue/recipient_drawer', []);
        $output .= parent::render();
        return $output;
    }
}
