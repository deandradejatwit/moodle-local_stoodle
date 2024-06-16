<?php
use Seld\JsonLint\Undefined;
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
$PAGE->set_url(new moodle_url('/local/flashcard_create/index.php'));
$PAGE->set_pagelayout('standard');
$PAGE->set_title(get_string('flashcardcreate', 'local_stoodle'));
$PAGE->set_heading(get_string('flashcardcreate', 'local_stoodle'));

$createcardsform = new \local_stoodle\form\create_cards();
if ($data = $createcardsform->get_data()) {
    $question = required_param_array('question', PARAM_TEXT);
    $answer = required_param_array('answer', PARAM_TEXT);
    if (!empty($question)&&!empty($answer)) {
        $record = new stdClass;
        for ($i=0; $i<=count($question)-1; $i++) {
            $record->flashcard_question = $question[$i];
            $record->flashcard_answer = $answer[$i];
            $DB->insert_record('flashcard_test', $record);
        }
    }
    $url = new moodle_url('/local/stoodle/flashcard.php');
    redirect($url);
}
echo $OUTPUT->header();
?>
<html lang="en">
<body>
    <div>
        Set:
        <select>
            <option value="option1"> Option 1 </option>
            <option value="option2"> Option 2 </option>
        </select>
        <a href=""><button type="submit">Submit</button></a>
    </div>
</body>
</html>

<?php
$createcardsform->display();

echo $OUTPUT->footer();