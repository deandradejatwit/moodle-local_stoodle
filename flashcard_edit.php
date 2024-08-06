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

require('../../config.php');

require_login();
global $error;

$url = new moodle_url('/local/stoodle/flashcard_edit.php', []);
$PAGE->set_url($url);
$PAGE->set_context(context_system::instance());
$PAGE->set_pagelayout('standard');
$PAGE->set_title(get_string('flashcardedit', 'local_stoodle'));
$PAGE->set_heading(get_string('flashcardedit', 'local_stoodle'));

// Instantiates the edit_set constructor to create the edit_set form.
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

    if (!empty($set) || check_not_empty($questions) || check_not_empty($answers)) {

        // Check to see if the new set name is not empty and doesn't already exits.
        if (!empty($set) && !$DB->get_record_select('stoodle_flashcard_set', 'name = ?  AND  usermodified = ?', [$set, $USER->id])) {
            $editset = new stdClass;

            $editset->id = $setid;
            $editset->name = $set;
            $editset->timemodified = time();

            $DB->update_record('stoodle_flashcard_set', $editset);
        }

        // Loops through both questions and answers array saving data to the database.
        for ($i = 0; $i <= count($questions); $i++) {
            if (!empty($questions[$i])) {

                $edits = new stdClass;

                $edits->id = $cardid[$i];
                $edits->question = $questions[$i];
                $edits->timemodified = time();
                $DB->update_record('stoodle_flashcards', $edits);

            }
            if (!empty($answers[$i])) {
                $edits = new stdClass;

                $edits->id = $cardid[$i];
                $edits->answer = $answers[$i];
                $edits->timemodified = time();
                $DB->update_record('stoodle_flashcards', $edits);
            }
        }

        $url = new moodle_url('/local/stoodle/flashcard_edit.php');
        redirect($url);
    } else {
        $error = true;
    }
}

/**
 * Checks if an array is empty
 *
 * @param array $arr1 First array
 */
function check_not_empty($arr1) {
    for ($i = 0; $i < count($arr1); $i++) {
        if (!(empty($arr1[$i]))) {
            return true;
        }
    }
    return false;
}

echo $OUTPUT->header();

if ($error) {
    echo $OUTPUT->notification(get_string('erredit', 'local_stoodle'), 'error');
}
$editsetform->display();

echo $OUTPUT->footer();
