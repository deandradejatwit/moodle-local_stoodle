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
namespace local_stoodle\form;
defined('MOODLE_INTERNAL') || die();
require_once($CFG->libdir . '/formslib.php');
class create_cards extends \moodleform {
    public function definition(){
        global $DB;
        $mform= $this->_form;

        /*$mform->addElement('textarea', 'question', get_string('question', 'local_stoodle'));
        $mform->setType('question', PARAM_TEXT);

        $mform->addElement('textarea', 'answer', get_string('answer', 'local_stoodle'));
        $mform->setType('answer', PARAM_TEXT);*/

        $submitlabel = get_string('submit');

        $repeatarray = [
            $mform->createElement('textarea', 'question', get_string('questionstr', 'local_stoodle')),
            $mform->createElement('textarea', 'answer', get_string('answerstr', 'local_stoodle')),
            $mform->createElement('submit', 'delete', get_string('deletestr', 'local_stoodle'), [], false),
        ];


        if ($this->_instance){
            $repeatno = $DB->count_records('choice_options', ['choiceid' => $this->_instance]);
            $repeatno += 2;
        } else {
            $repeatno = 3;
        }

        $repeateloptions = [
            'question'=> [],
            'answer'=> [],
        ];

        $mform->setType('question', PARAM_TEXT);
        $mform->setType('answer', PARAM_TEXT);

        $this->repeat_elements(
            $repeatarray,
            $repeatno,
            $repeateloptions,
            'option_repeats',
            'option_add_fields',
            1,
            null,
            true,
            'delete',
        );

        $mform->addElement('submit', 'submitmessage', $submitlabel);

    }
}
