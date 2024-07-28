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
$PAGE->set_url(new moodle_url('/local/stoodle/flashcard.php'));
$PAGE->set_pagelayout('standard');
$PAGE->set_title(get_string('pluginname', 'local_stoodle'));
$PAGE->set_heading("Flashcard Menu");  // Replace with get_string.

$SESSION->currentpage = 'flashcard';

$select = new \local_stoodle\form\select_form();
if ($select->no_submit_button_pressed()) {
    $data = $select->get_submitted_data();
    $set = required_param('card_sets', PARAM_TEXT);

    if ($set == -1) {
        $url = new moodle_url('/local/stoodle/flashcard_create.php');
        redirect($url);
    }

    $SESSION->edit_set_id = $set;

    $url = new moodle_url('/local/stoodle/flashcard_edit.php');
    redirect($url);
} else if ($select->is_cancelled()) {
    $data = $select->get_submitted_data();
    $set = required_param('card_sets', PARAM_TEXT);

    if ($set == -1) {
        $url = new moodle_url('/local/stoodle/flashcard_create.php');
        redirect($url);
    }
    $SESSION->edit_set_id = $set;
    $DB->delete_records_select('stoodle_flashcard_set', 'id = ?', [$set]);
    $DB->delete_records_select('stoodle_flashcards', 'stoodle_flashcard_setid = ?', [$set]);
    $url = new moodle_url('/local/stoodle/flashcard.php');
    redirect($url);
} else if ($data = $select->get_data()) {
    $set = required_param('card_sets', PARAM_TEXT);
    if ($set == -1) {
        $url = new moodle_url('/local/stoodle/flashcard_create.php');
        redirect($url);
    }
    $SESSION->activity_set_name = $set;
    $url = new moodle_url('/local/stoodle/flashcard_activity.php');
    redirect($url);
}

echo $OUTPUT->header();
$select->display();
?>

<html lang="en">

<body>
    <div class="nav-buttons">
        <a href="flashcard_create.php"><button class="btn btn-primary">Create New Set</button></a>
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
#id_editset.btn-primary{
    color: #1d2125;
    background-color: #ced4da;
    border-color: #ced4da;
}
#id_editset.btn-primary:hover {
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
