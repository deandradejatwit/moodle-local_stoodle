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

$PAGE->set_context(context_system::instance());
$PAGE->set_url(new moodle_url('/local/stoodle/index.php'));
$PAGE->set_pagelayout('standard');
$PAGE->set_title(get_string('pluginname', 'local_stoodle'));
$PAGE->set_heading(get_string('pluginname', 'local_stoodle'));

echo $OUTPUT->header();
?>

<html lang="en">

<body>
    <h3 id="instance-5-header" class="h5 card-title d-inline">Study Tools</h3>
    <hr>
    <div class=tools>
        <img class="flashcard-image" src="images/flashcards.png" alt="flashcards"><br>
        <p>Create, edit, save and study sets of flashcards.</p>
        <a href="flashcard.php"><button type="button" class="btn btn-lg btn-orange btn-flashcard">Flashcards</button></a>
    </div>
    <div class=tools>
        <img class="quiz-image" src="images/quiz.jpg" alt="quiz"><br>
        <p>Make and save quizzes to prepare for upcoming exams.</p>
        <a href="quiz.php"><button type="button" class="btn btn-lg btn-orange btn-quiz">Quiz</button></a>
    </div>
</body>
<style>
    .tools{
        margin-top: 1em;
        margin-bottom:2em;
        display:inline-block;
        margin-left: 1em;
        margin-right: 1em;
    }
    .flashcard-image{
        height: auto;
        width: 225px;
        margin-bottom:1em;

    }
    .quiz-image{
        height: auto;
        width: 125px;
        margin-bottom:1em;

    }
    body {
        text-align: center;
    }
    .btn-orange {
        background-color: #f98012;
    }
</style>

</html>
<?php
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
