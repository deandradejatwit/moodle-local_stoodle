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
 * TODO describe file quiz_edit
 *
 * @package    local_stoodle
 * @copyright  2024 Jonathan Kong-Shi kongshij@wit.edu,
 *              Myles R. Sullivan sullivanm22@wit.edu,
 *              Jhonathan Deandrade deandradej@wit.edu
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

require('../../config.php');

require_login();

$url = new moodle_url('/local/stoodle/quiz_edit.php', []);
$PAGE->set_url($url);
$PAGE->set_context(context_system::instance());
$PAGE->set_url(new moodle_url('/local/stoodle/quiz_edit.php'));
$PAGE->set_pagelayout('standard');
$PAGE->set_title(get_string('quizedit', 'local_stoodle'));
$PAGE->set_heading(get_string('quizedit', 'local_stoodle'));

// Instantiates the select_form constructor to create the select_form form.
$editquizform = new \local_stoodle\form\edit_quiz();
if ($editquizform->is_cancelled()) {
    $url = new moodle_url('/local/stoodle/quiz.php');
    redirect($url);
} else if ($data = $editquizform->get_data()) {
    $quizid = required_param('quizid', PARAM_INT);
    $optionid = required_param_array('optionid', PARAM_INT);
    $questionid = required_param_array('questionid', PARAM_INT);

    $yesarr = required_param_array('yes', PARAM_INT);
    $questions = required_param_array('questions', PARAM_TEXT);
    $options = required_param_array('options', PARAM_TEXT);

    if (!empty($quiz) || check_not_empty($questions) || check_not_empty($options) ||  check_not_empty($yesarr)) {

        // Check to see if the new quiz name is not empty and doesn't already exits.
        if (!empty($quiz) && !$DB->get_record_select('stoodle_quiz', 'name = ?', [$quiz])) {
            $editquiz = new stdClass;

            $editquiz->id = $quizid;
            $editquiz->name = $quiz;
            $editquiz->timemodified = time();

            $DB->update_record('stoodle_quiz', $editquiz);
        }

        // Loop through quiz array and save edited questions.
        for ($i = 0; $i <= count($questions); $i++) {
            if (!empty($questions[$i])) {

                $questionedits = new stdClass;

                $questionedits->id = $questionid[$i];
                $questionedits->question_text = $questions[$i];
                $questionedits->timemodified = time();
                $DB->update_record('stoodle_quiz_questions', $questionedits);

            }
        }

        $count = 0;
        // Loop through option array and save edited options.
        for ($j = 0; $j < count($options); $j++) {
            $optionedit = new stdClass;

            $o = $DB->get_record_select('stoodle_quiz_question_options', 'id = ?', [$optionid[$j]]);
            $q = $DB->get_record_select('stoodle_quiz_questions', 'id = ?', [$o->stoodle_quiz_questionsid]);

            if ($yesarr[$count] == 0 && $q->is_multiple_choice == 1) {
                $nextidx = $count + 1;
                if (isset($yesarr[$nextidx]) && $yesarr[$nextidx] == 1 ) {
                    $optionedit->id = $optionid[$j];
                    $optionedit->is_correct = '1';
                    $optionedit->timemodified = time();
                    $DB->update_record('stoodle_quiz_question_options', $optionedit);
                    $count = $count + 2;
                } else {
                    $optionedit->id = $optionid[$j];
                    $optionedit->is_correct = '0';
                    $optionedit->timemodified = time();
                    $DB->update_record('stoodle_quiz_question_options', $optionedit);
                    $count++;
                }
            }

            if (!empty($options[$j])) {
                $optionedit->id = $optionid[$j];
                $optionedit->option_text = $options[$j];
                $optionedit->timemodified = time();
                $DB->update_record('stoodle_quiz_question_options', $optionedit);
            }
        }

        $url = new moodle_url('/local/stoodle/quiz.php');
        redirect($url);
    } else {
        redirect(new moodle_url('/local/stoodle/quiz_edit.php'),get_string('erredit', 'local_stoodle'), '', 'error');
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

$editquizform->display();

echo $OUTPUT->footer();
