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
global $DB;

$PAGE->set_context(context_system::instance());
$PAGE->set_url(new moodle_url('/local/stoodle/quiz_activity.php'));
$PAGE->set_pagelayout('standard');
$PAGE->set_title(get_string('quizname', 'local_stoodle'));
$PAGE->set_heading(get_string('quizname', 'local_stoodle'));
$PAGE->requires->string_for_js('js_quiz_scoretext', 'local_stoodle');
$PAGE->requires->string_for_js('js_quiz_questiontext', 'local_stoodle');
$PAGE->requires->string_for_js('js_quiz_unansweredtext', 'local_stoodle');

$quizname = required_param('quiz_set_id', PARAM_TEXT);
$questions = $DB->get_records('stoodle_quiz_questions', ['stoodle_quizid' => $quizname, 'usermodified' => $USER->id]);

if($questions == null){
    redirect(new moodle_url('/local/stoodle/quiz.php'));
}


$questionset = json_encode($questions);
$answerset = json_encode($DB->get_records('stoodle_quiz_question_options'));


echo $OUTPUT->header();

$PAGE->requires->js_call_amd('local_stoodle/quiz', 'init');

$templatecontext = (object)[
    'database_questions' => $questionset,
    'database_answers' => $answerset,
    'exit_string' => get_string('exitstr', 'local_stoodle'),
];

echo $OUTPUT->render_from_template('local_stoodle/quiz_activity', $templatecontext);

echo $OUTPUT->footer();
