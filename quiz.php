<?php
// This file is part of Moodle - https://moodle.org/
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
// along with Moodle.  If not, see <https://www.gnu.org/licenses/>.

/**
 *
 *
 * @package     local_stoodle
 * @copyright   2024 Jonathan Kong-Shi kongshij@wit.edu,
 *              Myles R. Sullivan sullivanm22@wit.edu,
 *              Jhonathan Deandrade deandradej@wit.edu
 * @license     https://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
require_once('../../config.php');
require_login();
global $SESSION;

$PAGE->set_context(context_system::instance());
$PAGE->set_url(new moodle_url('/local/stoodle/quiz.php'));
$PAGE->set_pagelayout('standard');
$PAGE->set_title(get_string('pluginname', 'local_stoodle'));
$PAGE->set_heading("Quiz Menu");  // Replace with get_string.

$SESSION->currentpage = 'quiz';

$select = new \local_stoodle\form\select_form();
if ($select->is_cancelled()) {
    $url = new moodle_url('/local/stoodle/index.php');
    redirect($url);
} else if ($data = $select->get_data()) {
    $set = required_param('card_sets', PARAM_TEXT);
    if ($set == -1) {
        $url = new moodle_url('/local/stoodle/flashcard_create.php');
        redirect($url);
    }
    $SESSION->quiz_set_name = $set;
    $url = new moodle_url('/local/stoodle/quiz.php');
    redirect($url);
}

echo $OUTPUT->header();
$select->display();
echo $OUTPUT->footer();
