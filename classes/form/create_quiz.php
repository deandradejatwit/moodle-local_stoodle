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

namespace local_stoodle\form;

/**
 * Class create_quiz
 *
 * @package    local_stoodle
 * @copyright  2024 Jonathan Kong-Shi kongshij@wit.edu,
 *              Myles R. Sullivan sullivanm22@wit.edu,
 *              Jhonathan Deandrade deandradej@wit.edu
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die();
require_once($CFG->libdir . '/formslib.php');

class create_quiz extends \moodleform {
    public function definition() {
        global $DB, $SESSION;

        $name = $SESSION->quiz_name;

        $mform = $this->_form;

        $mform->registerNoSubmitButton('add');

        if(empty($name)){
            $front= [ $mform->createElement('textarea', 'quiz', get_string('quiznamestr', 'local_stoodle'))];
            $mform->setType('quiz', PARAM_TEXT);
            $mform->addElement($front[0]);
        } else {
            $quiz = $DB->get_record_select('stoodle_quiz', 'name = ?', [$name]);
            $SESSION->quiz_id = $quiz->id;
            $countO = 1;
            $countQ = 1;

            $front = [ $mform->createElement('static','quizname', get_string('currentquizname', 'local_stoodle'), $quiz->name)];
            $mform->addElement($front[0]);

            if ($DB->get_records_select('stoodle_quiz_questions', 'stoodle_quizid = ?', [$quiz->id])) {
                $questions  = $DB->get_records_select('stoodle_quiz_questions', 'stoodle_quizid = ?', [$quiz->id]);
                foreach ($questions as $question) {
                    $answers  = $DB->get_records_select('stoodle_quiz_question_options', 'stoodle_quiz_questionsid = ?', [$question->id]);
                    $mform->addElement('static','questiontext', get_string('quizquestion', 'local_stoodle'). ' ' . $countQ . ':', $question->question_text);
                    foreach ($answers as $answer) {
                        $mform->addElement('static','optiontext', get_string('quizoption', 'local_stoodle'). ' ' . $countO . ':', $answer->option_text);
                        $countO++;
                    }
                    $countQ++;
                    $countO = 1;
                }
                $countQ = 1;
            }

            $mform->addElement('submit','add', get_string('addquestion','local_stoodle'));
        }

        $name = null;
        $this->add_action_buttons();

    }
}
