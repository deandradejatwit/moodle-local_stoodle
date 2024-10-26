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
global $DB, $SESSION;

$PAGE->set_context(context_system::instance());
$PAGE->set_url(new moodle_url('/local/stoodle/flashcard_activity.php'));
$PAGE->set_pagelayout('standard');
$PAGE->set_title(get_string('flashcardactivityname', 'local_stoodle'));
$PAGE->set_heading(get_string('flashcardactivityname', 'local_stoodle'));
$PAGE->requires->string_for_js('js_cardnumber', 'local_stoodle');

$flashcardid = required_param('set', PARAM_TEXT);
$flashcardset = json_encode($DB->get_records('stoodle_flashcards',
['stoodle_flashcard_setid' => $flashcardid, 'usermodified' => $USER->id]));

echo $OUTPUT->header();


$PAGE->requires->js_call_amd('local_stoodle/flashcard', 'init');

$templatecontext = (object)[
    'database_flashcards' => $flashcardset,
    'exit_string' => get_string('exitstr', 'local_stoodle'),
];

echo $OUTPUT->render_from_template('local_stoodle/flashcard_activity', $templatecontext);

echo $OUTPUT->footer();
