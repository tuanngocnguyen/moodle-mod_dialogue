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

use cache;
use context_course;
use user_picture;

defined('MOODLE_INTERNAL') || die();

class course_participants_cache {
    
    /** @var int $courseid The course. */
    protected $courseid;
    
    /** @var cache $cache The cache store. */
    protected $cache;
    
    /**
     * Constructor.
     *
     * course_participants_cache constructor.
     * @param int $courseid
     * @throws \coding_exception
     */
    public function __construct(int $courseid) {
        $this->courseid = $courseid;
        $this->load_for_courseid($courseid);
    }
    
    /**
     * Set up course participants cache contains users profile image URL and their
     * role assignments in the course.
     *
     * @param $courseid
     * @throws \coding_exception
     */
    protected function load_for_courseid($courseid) {
        global $PAGE;
        $context = context_course::instance($courseid);
        $this->cache = cache::make('mod_dialogue', 'courseparticipants', ['courseid' => $courseid]);
        $allroleassignments = get_users_roles($context, [], false, 'c.contextlevel DESC, r.sortorder ASC');
        $rs = user_get_participants($courseid, 0, 0, 0, 0, -1, '');
        foreach ($rs as $user) {
            $userpicture = new user_picture($user);
            $user->userprofileimageurl = $userpicture->get_url($PAGE)->out();
            $user->roleassignments = isset($allroleassignments[$user->id]) ? $allroleassignments[$user->id] : false;
            $this->cache->set($user->id, $user);
        }
        $rs->close();
    }
    
    /**
     * Wrapper method for cache store.
     *
     * @param int $id
     * @return false|mixed
     * @throws \coding_exception
     */
    public function get(int $id) {
        return $this->cache->get($id);
    }
}
