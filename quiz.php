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
$SESSION->question_count = 0;
$SESSION->quiz_id = null;
$SESSION->quiz_name = null;

$select = new \local_stoodle\form\select_form();

// If the create button is pressed pass selected quiz to quiz_create create page and redirect.
if ($select->no_submit_button_pressed()) {
    $data = $select->get_submitted_data();
    $quiz = required_param('quizzes', PARAM_TEXT);

    // If no quiz exist in dropdown redirect to flashcard_create.
    if ($quiz == -1) {
        $url = new moodle_url('/local/stoodle/quiz_create.php');
        redirect($url);
    }

    $SESSION->edit_quiz_id = $quiz;

    $url = new moodle_url('/local/stoodle/quiz_edit.php');
    redirect($url);
    // If delete button is pressed delete selected quiz and refresh page.
} else if ($select->is_cancelled()) {
    $data = $select->get_submitted_data();
    $quiz = required_param('quizzes', PARAM_TEXT);

    if ($quiz == -1) {
        $url = new moodle_url('/local/stoodle/quiz_create.php');
        redirect($url);
    }

    $DB->delete_records_select('stoodle_quiz', 'id = ?', [$quiz]);
    $questions = $DB->get_records_select('stoodle_quiz_questions', 'stoodle_quizid = ?', [$quiz]);

    foreach ($questions as $question) {
        $DB->delete_records_select('stoodle_quiz_question_options', 'stoodle_quiz_questionsid = ?', [$question->id]);
    }

    $DB->delete_records_select('stoodle_quiz_questions', 'stoodle_quizid = ?', [$quiz]);

    $url = new moodle_url('/local/stoodle/quiz.php');
    redirect($url);

    // If submit is pressed pass selected set to quiz_activity and redirect.
} else if ($data = $select->get_data()) {
    $quiz = required_param('quizzes', PARAM_TEXT);

    if ($quiz == -1) {
        $url = new moodle_url('/local/stoodle/quiz_create.php');
        redirect($url);
    }
    $SESSION->quiz_set_name = $quiz;
    $url = new moodle_url('/local/stoodle/quiz_activity.php');
    redirect($url);
}

echo $OUTPUT->header();

$select->display();
?>

<html lang="en">
<body>
    <div class="nav-buttons">
        <a href="quiz_create.php"><button class="btn btn-primary">Create New Quiz</button></a>
        <a href="index.php"><button class="btn btn-secondary">Back</button></a>
    </div>
</body>
<style>
    .nav-buttons{
        margin-left: 202.25px;
    }
#id_delete.btn-primary{
    color: #fff;
    background-color: #ca3120;
    border-color: #ca3120;
}
#id_delete.btn-primary:hover {
    color: #fff;
    background-color: #a9291b;
    border-color: #9e2619;
}
#id_editquiz.btn-primary{
    color: #1d2125;
    background-color: #ced4da;
    border-color: #ced4da;
}
#id_editquiz.btn-primary:hover {
    color: #1d2125;
    background-color: #b8c1ca;
    border-color: #b1bbc4;
}
</style>
</html>

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
echo $OUTPUT->footer();
