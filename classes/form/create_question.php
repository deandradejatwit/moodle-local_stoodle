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
 * Class create_question
 *
 * @package    local_stoodle
 * @copyright  2024 YOUR NAME <your@email.com>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
defined('MOODLE_INTERNAL') || die();
require_once($CFG->libdir . '/formslib.php');

class create_question extends \moodleform {
    public function definition() {
        global $DB, $SESSION;
        $mform = $this->_form;

        $mform->addElement('textarea','question', get_string('questionstr','local_stoodle'));

        $startarray = [
            $mform->createElement('textarea', 'answer', get_string('answerstr', 'local_stoodle')),
        ];

        $repeatno = 1;

        $repeateloptions = [
            'answer' => [],
        ];

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

        $mform->addElement('text','correctanswer', get_string('correctstr','local_stoodle'));
        $mform->setType('correctanswer', PARAM_INT);
        $mform->setDefault('correctanswer',0);
        $mform->addHelpButton('correctanswer', 'entereanswer', 'local_stoodle');
        $this->add_action_buttons();
    }
}
