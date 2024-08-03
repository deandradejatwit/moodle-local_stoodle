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
 * Class create_question
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
 * create repeating Question form.
 *
 */
class create_question extends \moodleform {
    /**
     * defining the functionality and structure of form
     *
     */
    public function definition() {
        global $DB, $SESSION;
        $mform = $this->_form;

        $mform->addElement('textarea', 'question', get_string('questionstr', 'local_stoodle'));

        $startarray = [
            $mform->createElement('textarea', 'answer', get_string('answerstr', 'local_stoodle')),
            $mform->createElement('advcheckbox', 'optradio', get_string('selectanswerstr', 'local_stoodle'), '', ['group' => 1], [0, 1]),
        ];

        $repeatno = 1;

        $repeateloptions = [
            'answer' => [],
        ];
        $mform->setType('optradio', PARAM_INT);
        $mform->setType('question', PARAM_TEXT);
        $mform->setType('answer', PARAM_TEXT);

        $this->repeat_elements(
            $startarray,
            $repeatno,
            $repeateloptions,
            'option_repeats',
            'option_add_fields',
            1,
            null,
            true,
            'delete',
        );
        $this->add_action_buttons();
    }
}
