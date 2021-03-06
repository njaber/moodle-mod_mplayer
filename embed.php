<?php
/**
 * This page prints a particular instance of mplayer without any standard Moodle navigation
 * It's useful for embedding video in Moodle web pages
 *
 * @author Matt Bury - matbury@gmail.com
 * @version $Id: embed.php,v 1.1 2010/03/05 matbury Exp $
 * @licence http://www.gnu.org/copyleft/gpl.html GNU Public Licence
 * @package mplayer
 **/

/**    Copyright (C) 2009  Matt Bury
*
*    This program is free software: you can redistribute it and/or modify
*    it under the terms of the GNU General Public License as published by
*    the Free Software Foundation, either version 3 of the License, or
*    (at your option) any later version.
*
*    This program is distributed in the hope that it will be useful,
*    but WITHOUT ANY WARRANTY; without even the implied warranty of
*    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
*    GNU General Public License for more details.
*
*    You should have received a copy of the GNU General Public License
*    along with this program.  If not, see <http://www.gnu.org/licenses/>.
*/
require_once('../../config.php');
require_once('lib.php');
$id = optional_param('id', 0, PARAM_INT); // Course Module ID, or
$a  = optional_param('a', 0, PARAM_INT);  // mplayer ID
if ($id) {
    if (! $cm = $DB->get_record('course_modules', array('id' => $id))) {
        print_error('invalidcoursemodule');
    }
    if (! $course = $DB->get_record('course', array('id' => $cm->course))) {
        print_error('coursemisconf');
    }
    if (! $mplayer = $DB->get_record('mplayer', array('id' => $cm->instance))) {
        print_error('invalidmplayerid', 'mplayer');
    }
} else {
    if (! $mplayer = $DB->get_record('mplayer', array('id' => $a))) {
        print_error('invalidmplayerid', 'mplayer');
    }
    if (! $course = $DB->get_record('course', array('id' => $mplayer->course))) {
        print_error('coursemisconf');
    }
    if (! $cm = get_coursemodule_from_instance('mplayer', $mplayer->id, $course->id)) {
        print_error('invalidcoursemodule');
    }
}
// Make sure the user's logged in
require_login($course->id);
// Insert a record into Moodle log
add_to_log($course->id, 'mplayer', 'view', 'view.php?id='.$cm->id, "$mplayer->id");
// Add instance ID to mplayer object
$mplayer->instance = $id;
// Don't print a Moodle header since this page will be included in another Moodle page as an iframe
// Print the Media Player Flash embed code
echo mplayer_print_body($mplayer);
// Don't print any footers here.

