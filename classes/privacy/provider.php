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

namespace local_stoodle\privacy;
use core_privacy\local\metadata\collection;
defined('MOODLE_INTERNAL') || die();

/**
 * This plugin collects information
 */
class provider implements \core_privacy\local\metadata\provider {
    /**
     * Called by the Moodle Privacy API interface.
     * @param collection $collection Moodle privacy collection object.
     * @return collection The updated Moodle privacy collection object.
     */
    public static function get_metadata(collection $collection) {
        // Description for use of core_form.
        $collection->add_subsystem_link(
            'core_form',
            [],
            'privacy:metadata:core_form'
        );

        // Description for mdl_stoodle_flashcards table.
        $collection->add_database_table(
            'stoodle_flashcards',
            [
                'question' => 'privacy:metadata:stoodle_flashcards:question',
                'answer' => 'privacy:metadata:stoodle_flashcards:answer',
                'usermodified' => 'privacy:metadata:stoodle_flashcards:usermodified',
            ],
            'privacy:metadata:stoodle_flashcards'
        );

        // Description for mdl_stoodle_flashcard_set table.
        $collection->add_database_table(
            'stoodle_flashcard_set',
            [
                'name' => 'privacy:metadata:stoodle_flashcard_set:name',
                'usermodified' => 'privacy:metadata:stoodle_flashcard_set:usermodified',
            ],
            'privacy:metadata:stoodle_flashcard_set'
        );


        return $collection;
    }
}
