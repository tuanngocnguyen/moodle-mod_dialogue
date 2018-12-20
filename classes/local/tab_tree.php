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

namespace mod_dialogue\local;

defined('MOODLE_INTERNAL') || die();

use moodle_url;
use tabobject;
use tabtree;

/**
 * Wrapper class for building a tab tree navigation structure for the Dialogue module.
 *
 * @package   mod_dialogue
 * @copyright 2018 Troy Williams
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class tab_tree {
    /**
     * Build a Moodle tab tree based for dialogue module.
     *
     * @return tabtree
     * @throws \coding_exception
     * @throws \moodle_exception
     */
    public static function build_navigation() {
        global $PAGE;
        
        $context   = $PAGE->context;
        $cm        = $PAGE->cm;
        $url       = $PAGE->url;
        $activetab = null;
        $tabs      = [];
        $tabs['view'] = new tabobject(
            'view',
            new moodle_url('/mod/dialogue/view.php', ['id' => $cm->id]),
            get_string('viewconversations', 'dialogue')
        );
        $tabs['drafts'] = new tabobject(
            'drafts',
            new moodle_url('/mod/dialogue/drafts.php', ['id' => $cm->id]),
            get_string('drafts', 'dialogue')
        );
        if (has_any_capability(['mod/dialogue:bulkopenrulecreate', 'mod/dialogue:bulkopenruleeditany'], $context)) {
            $tabs['bulkopenrules'] = new tabobject(
                'bulkopenrules',
                new moodle_url('/mod/dialogue/bulkopenrules.php', ['id' => $cm->id]),
                get_string('bulkopenrules', 'dialogue')
            );
        }
        $currentpage = basename($url->out_omit_querystring(), '.php');
        if (in_array($currentpage, array_keys($tabs))) {
            $activetab = $currentpage;
        }
        return new tabtree($tabs, $activetab);
    }
}
