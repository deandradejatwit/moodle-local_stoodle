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
 * TODO describe file question_create
 *
 * @package    local_stoodle
 * @copyright  2024 YOUR NAME <your@email.com>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

require ('../../config.php');

require_login();
global $SESSION;

$url = new moodle_url('/local/stoodle/question_create.php', []);
$PAGE->set_url($url);
$PAGE->set_context(context_system::instance());
$PAGE->set_pagelayout('standard');
$PAGE->set_title(get_string('createquestion', 'local_stoodle'));
$PAGE->set_heading(get_string('createquestion', 'local_stoodle'));

$createquestionform = new \local_stoodle\form\create_question();
if ($createquestionform->is_cancelled()) {
    $SESSION->question_count -= 1;
    $url = new moodle_url('/local/stoodle/quiz_create.php');
    redirect($url);
} else if ($data = $createquestionform->get_data()) {
    $question = required_param('question', PARAM_TEXT);
    $answer = required_param_array('answer', PARAM_TEXT);
    $correctanswerindex = required_param('correctanswer', PARAM_INT);

    $question_num = $SESSION->question_count;
    $quizID = $SESSION->quiz_id;

    if (!empty($question) && check_empty($answer)) {

        $recordquestion = new stdClass;
        $recordanswers = new stdClass;

        $recordquestion->stoodle_quizid = $quizID;
        $recordquestion->question_number = $question_num;
        $recordquestion->question_text = $question;
        $recordquestion->usermodified = $USER->id;
        $recordquestion->timecreated = time();
        $recordquestion->timemodified = time();

        if(count($answer) > 1){
            $recordquestion->is_multiple_choice = 1;
        } else {
            $recordquestion->is_multiple_choice = 0;
        }

        if ($DB->get_record_select('stoodle_quiz', 'id = ?', [$quizID]) && !$DB->get_record_select('stoodle_quiz_questions', 'question_text = ?', [$question])) {
            $DB->insert_record('stoodle_quiz_questions', $recordquestion);
            $ques = $DB->get_record_select('stoodle_quiz_questions', 'question_text = ?', [$question]);

            for ($i = 0; $i <= count($answer) - 1; $i++) {
                if (!empty($answer[$i])) {
                    if($i == $correctanswerindex){
                        $recordanswers->is_correct = 1;
                    } else {
                        $recordanswers->is_correct = 0;
                    }

                    $recordanswers->stoodle_quiz_questionsid = $ques->id;
                    $recordanswers->option_number = $i + 1;
                    $recordanswers->option_text = $answer[$i];
                    $recordanswers->usermodified = $USER->id;
                    $recordanswers->timecreated = time();
                    $recordanswers->timemodified = time();
                    $DB->insert_record('stoodle_quiz_question_options', $recordanswers);
                }
            }
            redirect(new moodle_url('/local/stoodle/quiz_create.php'));
        }
    }
}

/**
 * Checks if two arrays are empty
 *
 * @param array $arr1 First array
 * @param array $arr2 Second array
 */
function check_empty($arr1)
{
    for ($i = 0; $i < count($arr1); $i++) {
        if (!(empty($arr1[$i]))) {
            return true;
        }
    }
    return false;
}

echo $OUTPUT->header();

$createquestionform->display();

echo $OUTPUT->footer();
