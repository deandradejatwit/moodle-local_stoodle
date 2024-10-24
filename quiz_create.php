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
 * TODO describe file quiz_create
 *
 * @package    local_stoodle
 * @copyright  2024 Jonathan Kong-Shi kongshij@wit.edu,
 *              Myles R. Sullivan sullivanm22@wit.edu,
 *              Jhonathan Deandrade deandradej@wit.edu
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

require('../../config.php');

require_login();

$url = new moodle_url('/local/stoodle/quiz_create.php', []);
$PAGE->set_url($url);
$PAGE->set_context(context_system::instance());
$PAGE->set_pagelayout('standard');
$PAGE->set_title(get_string('createquiz', 'local_stoodle'));
$PAGE->set_heading(get_string('createquiz', 'local_stoodle'));

// Instantiates the create_quiz constructor to create the create_quiz form.
$createquizform = new \local_stoodle\form\create_quiz();
if ($createquizform->no_submit_button_pressed()) {
    $name = required_param('quiz', PARAM_TEXT);
    $SESSION->question_count += 1;
    $url = new moodle_url('/local/stoodle/question_create.php', ['quizname'=>$name]);
    redirect($url);

    // If cancel button pressed delete quiz name and any created questions from the database.
} else if ($createquizform->is_cancelled()) {

    $name = required_param('quiz', PARAM_TEXT);

    if (!empty($name)) {

        $quiz = $DB->get_record_select('stoodle_quiz', 'name = ?', [$name]);
        $questions = $DB->get_records_select('stoodle_quiz_questions', 'stoodle_quizid = ?', [$quiz->id]);
        foreach ($questions as $question) {
            $DB->delete_records_select('stoodle_quiz_question_options', 'stoodle_quiz_questionsid = ?', [$question->id]);
        }
        $DB->delete_records_select('stoodle_quiz_questions', 'stoodle_quizid = ?', [$quiz->id]);
        $DB->delete_records_select('stoodle_quiz', 'name = ?', [$name]);

        $SESSION->question_count = 0;
    }


    $SESSION->question_count = 0;

    $url = new moodle_url('/local/stoodle/quiz.php');
    redirect($url);
} else if ($data = $createquizform->get_data()) {
    $name = optional_param('quiz', '', PARAM_TEXT);

    // Checks if created quiz name does not exists in the flashcard set database, if it does go on with quiz creation.
    if (!empty($name) && !$DB->get_record_select('stoodle_quiz', 'name = ? && usermodified = ?', [$name, $USER->id])) {
        $record = new stdClass;
        $record->name = $name;
        $record->usermodified = $USER->id;
        $record->timecreated = time();
        $record->timemodified = time();
        $DB->insert_record('stoodle_quiz', $record);
        redirect(new moodle_url('/local/stoodle/quiz_create.php', ['quizname' => $name]));
    } else if (empty($name) && $DB->get_record_select('stoodle_quiz', 'name = ? && usermodified = ?', [$name, $USER->id])) {
        redirect(new moodle_url('/local/stoodle/quiz_create.php', ['quizname' => $name]),get_string('errquizcreate', 'local_stoodle'), '', 'error');
    } else {
        $SESSION->question_count = 0;
        redirect(new moodle_url('/local/stoodle/quiz.php'));
    }
}
echo $OUTPUT->header();

$createquizform->display();

echo $OUTPUT->footer();
