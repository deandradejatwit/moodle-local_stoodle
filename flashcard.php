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
$PAGE->set_url(new moodle_url('/local/stoodle/flashcard.php'));
$PAGE->set_pagelayout('standard');
$PAGE->set_title(get_string('pluginname', 'local_stoodle'));
$PAGE->set_heading(get_string('flashcard_menu', 'local_stoodle'));

$SESSION->currentpage = 'flashcard';

// Instantiates the select_form constructor to create the select_form form.
$select = new \local_stoodle\form\select_form();

// If the create button is pressed pass selected set to flashcard_create create page and redirect.
if ($select->no_submit_button_pressed()) {
    $data = $select->get_submitted_data();
    $set = required_param('card_sets', PARAM_TEXT);

    // If no set exist in dropdown redirect to flashcard_create.
    if ($set == -1) {
        $url = new moodle_url('/local/stoodle/flashcard_create.php');
        redirect($url);
    }

    $url = new moodle_url('/local/stoodle/flashcard_edit.php', ['edit_set_id' => $set]);
    redirect($url);
    // If delete button is pressed delete selected set and refresh page.
} else if ($select->is_cancelled()) {
    $data = $select->get_submitted_data();
    $set = required_param('card_sets', PARAM_TEXT);

    if ($set == -1) {
        $url = new moodle_url('/local/stoodle/flashcard_create.php');
        redirect($url);
    }
    $DB->delete_records_select('stoodle_flashcard_set', 'id = ?', [$set]);
    $DB->delete_records_select('stoodle_flashcards', 'stoodle_flashcard_setid = ?', [$set]);
    $url = new moodle_url('/local/stoodle/flashcard.php');
    redirect($url);

    // If submit is pressed pass selected set to flashcard_activity and redirect.
} else if ($data = $select->get_data()) {
    $set = required_param('card_sets', PARAM_TEXT);
    if ($set == -1) {
        $url = new moodle_url('/local/stoodle/flashcard_create.php');
        redirect($url);
    }
    $url = new moodle_url('/local/stoodle/flashcard_activity.php', ['set' => $set]);
    redirect($url);
}

echo $OUTPUT->header();

$select->display();

$templatecontext = (object)[
    'back_string' => get_string('backstr', 'local_stoodle'),
    'create_string' => get_string('createsetstr', 'local_stoodle'),
];

echo $OUTPUT->render_from_template('local_stoodle/flashcard', $templatecontext);

echo $OUTPUT->footer();
