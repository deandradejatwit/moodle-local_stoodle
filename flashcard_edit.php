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
 * TODO describe file flashcard_edit
 *
 * @package    local_stoodle
 * @copyright  2024 Jonathan Kong-Shi kongshij@wit.edu,
 *             Myles R. Sullivan sullivanm22@wit.edu,
 *             Jhonathan Deandrade deandradej@wit.edu
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

require ('../../config.php');

require_login();

$url = new moodle_url('/local/stoodle/flashcard_edit.php', []);
$PAGE->set_url($url);
$PAGE->set_context(context_system::instance());
$PAGE->set_pagelayout('standard');
$PAGE->set_title(get_string('flashcardedit', 'local_stoodle'));
$PAGE->set_heading(get_string('flashcardedit', 'local_stoodle'));

$editsetform = new \local_stoodle\form\edit_set();

if ($editsetform->is_cancelled()) {
    $url = new moodle_url('/local/stoodle/flashcard.php');
    redirect($url);
} else if ($data = $editsetform->get_data()) {
    $setid = required_param('setid', PARAM_INT);
    $cardid = required_param_array('cardid', PARAM_INT);

    $set = required_param('setname', PARAM_TEXT);
    $questions = required_param_array('questions', PARAM_TEXT);
    $answers = required_param_array('answers', PARAM_TEXT);

    if (!empty($set)) {
        $editset = new stdClass;

        $editset->id = $setid;
        $editset->name = $set;
        $editset->timemodified = time();

        $DB->update_record('stoodle_flashcard_set', $editset);
        $url = new moodle_url('/local/stoodle/flashcard.php');
        redirect($url);
    }

    if (check_empty($questions, $answers)) {

        for ($i = 0; $i <= count($questions); $i++) {
            if (!empty($questions[$i]) && !empty($answers[$i])) {

                $edits = new stdClass;

                $edits->id = $cardid[$i];
                $edits->question = $questions[$i];
                $edits->answer = $answers[$i];
                $edits->timemodified = time();
                $DB->update_record('stoodle_flashcards', $edits);
            }
        }

        $url = new moodle_url('/local/stoodle/flashcard.php');
        redirect($url);
    }
}

/**
 * Checks if two arrays are empty
 *
 * @param array $arr1 First array
 * @param array $arr2 Second array
 */
function check_empty($arr1, $arr2) {
    for ($i = 0; $i < count($arr1); $i++) {
        if (!(empty($arr1[$i]) || empty($arr2[$i]))) {
            return true;
        }
    }
    return false;
}

echo $OUTPUT->header();

$editsetform->display();

echo $OUTPUT->footer();
