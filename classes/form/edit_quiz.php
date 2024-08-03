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
 * Class edit_quiz
 *
 * @package    local_stoodle
 * @copyright  2024 Jonathan Kong-Shi kongshij@wit.edu,
 *              Myles R. Sullivan sullivanm22@wit.edu,
 *              Jhonathan Deandrade deandradej@wit.edu
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
namespace local_stoodle\form;
defined('MOODLE_INTERNAL') || die();
require_once($CFG->libdir . '/formslib.php');

/**
 * create quiz edit form.
 *
 */
class edit_quiz extends \moodleform {
    /**
     * defining the functionality and structure of form
     *
     */
    public function definition() {
        global $DB, $SESSION;
        $mform = $this->_form;

        $quizid = $SESSION->edit_quiz_id;
        $quiz = $DB->get_record('stoodle_quiz', array('id' => $quizid), 'name');
        $questions = $DB->get_records_list('stoodle_quiz_questions', 'stoodle_quizid', array('stoodle_quizid' => $quizid), '', '*');

        $mform->addElement('hidden', 'quizid', $quizid);
        $mform->addElement('static', 'priorquiz', get_string('currentquizname', 'local_stoodle'), $quiz->name);
        $mform->addElement('textarea', 'quizname', get_string('quiznamestr', 'local_stoodle'));

        $mform->setType('quizname', PARAM_TEXT);
        $mform->setType('sequizidtid', PARAM_INT);

        foreach ($questions as $question) {
            $count = 0;
            $mform->addElement('hidden', 'questionid[]', $question->id);

            $options = $DB->get_records_list('stoodle_quiz_question_options', 'stoodle_quiz_questionsid', array('stoodle_quiz_questionsid' => $question->id), '', '*');

            $mform->addElement('static', 'priorquestion', get_string('currentquestion', 'local_stoodle'), $question->question_text);
            $mform->addElement('textarea', 'questions[]', get_string('questionstr', 'local_stoodle'));

            foreach ($options as $option) {
                $mform->addElement('hidden', 'optionid[]', $option->id);
                $mform->addElement('static', 'prioroption', get_string('currentoption', 'local_stoodle'), $option->option_text);
                $mform->addElement('textarea', 'options[]', get_string('optionstr', 'local_stoodle'));

                $mform->setType('options[]', PARAM_TEXT);
                $count++;
            }

            $mform->addElement('hidden', 'optioncount[]', $count);

        }

        $mform->setType('quizid',  PARAM_INT);
        $mform->setType('optioncount[]',  PARAM_INT);
        $mform->setType('optionid[]',  PARAM_INT);
        $mform->setType('questionid[]',  PARAM_INT);
        $mform->setType('questions[]', PARAM_TEXT);
        $this->add_action_buttons();
    }


}
