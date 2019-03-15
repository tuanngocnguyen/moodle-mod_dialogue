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

require_once('HTML/QuickForm/element.php');
require_once(__DIR__.'/../../../../../lib/formslib.php');

class MoodleQuickForm_recipientpicker extends HTML_QuickForm_element {
    public function __construct($elementName = null, $elementLabel = null, $options = null, $attributes = null) {
        $this->_options = array();
        if ($attributes === null) {
            $attributes = array();
        }
        self::setLabel($elementLabel);
        /* padding: 0 0.5rem; */
       // $html = '<div class="recipient-chooser-popper popover-region collapsed popover-region-notifications" id="nav-notification-popover-container" data-userid="2" data-region="popover-region"><i class="icon fa fa-user-plus fa-fw" aria-hidden="true" title="Toggle recipient chooser" aria-label="Toggle recipient chooser"></i></div>';
        
        $this->_type = 'recipientpicker';
    }
    public function toHtml() {
        global $PAGE;
        
        return '<input type="hidden">';
    }
    
    public function accept(&$renderer, $required = false, $error = null) {
        global $PAGE;
 //mtrace('IN');
        $renderer->renderElement($this, $required, $error);
    }
    
    public function setLabel($label = null) {
    
        $html = '<div class="recipient-chooser-popper popover-region collapsed popover-region-notifications" id="nav-notification-popover-container" data-userid="2" data-region="popover-region"><i class="icon fa fa-user-plus fa-fw" aria-hidden="true" title="Toggle recipient chooser" aria-label="Toggle recipient chooser"></i></div>';
        
        
        $html = '<a class="recipient-chooser-popper1" href="#"><i class="icon fa fa-user-plus fa-fw" aria-hidden="true" title="Toggle recipient chooser" aria-label="Toggle recipient chooser"></i></a>';
        
        parent::setLabel($html);
    }
}
