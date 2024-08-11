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
 * Class edit_set
 *
 * @package    local_stoodle
 * @copyright  2024 Jonathan Kong-Shi kongshij@wit.edu,
 *             Myles R. Sullivan sullivanm22@wit.edu,
 *             Jhonathan Deandrade deandradej@wit.edu
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
namespace local_stoodle\form;
defined('MOODLE_INTERNAL') || die();
require_once($CFG->libdir . '/formslib.php');

/**
 * create flashcard edit form.
 *
 */
class edit_set extends \moodleform {
    /**
     * defining the functionality and structure of edit_set form.
     *
     */
    public function definition() {
        global $DB, $SESSION;
        $mform = $this->_form;

        $setid = $SESSION->edit_set_id;
        $set = $DB->get_record('stoodle_flashcard_set', ['id' => $setid], 'name');
        $setcards = $DB->get_records_list('stoodle_flashcards', 'stoodle_flashcard_setid',
        ['stoodle_flashcard_setid' => $setid], '', '*');

        $SESSION->test = $setcards;

        $mform->addElement('hidden', 'setid', $setid);
        $mform->addElement('static', 'priorquestion', get_string('currentsetname', 'local_stoodle'), $set->name);
        $mform->addElement('textarea', 'setname', get_string('setnamestr', 'local_stoodle'));

        $mform->setType('setname', PARAM_TEXT);
        $mform->setType('setid', PARAM_INT);

        foreach ($setcards as $setcard) {
            $mform->addElement('hidden', 'cardid[]', $setcard->id);

            $mform->addElement('static', 'priorquestion', get_string('currentquestion', 'local_stoodle'), $setcard->question);
            $mform->addElement('textarea', 'questions[]', get_string('questionstr', 'local_stoodle'));
            $mform->addElement('static', 'prioranswer', get_string('currentanswer', 'local_stoodle'), $setcard->answer);
            $mform->addElement('textarea', 'answers[]', get_string('answerstr', 'local_stoodle'));

        }

        $mform->setType('cardid[]',  PARAM_INT);
        $mform->setType('questions[]', PARAM_TEXT);
        $mform->setType('answers[]', PARAM_TEXT);
        $this->add_action_buttons();
    }
}
