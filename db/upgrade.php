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
 * This file is used to upgarde the database for stoodle.
 *
 * @package     local_stoodle
 * @copyright   2024 Jonathan Kong-Shi kongshij@wit.edu,
 *              Myles R. Sullivan sullivanm22@wit.edu,
 *              Jhonathan Deandrade deandradej@wit.edu
 * @license     https://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die();

 /**
  * This function updates the database of the local plugin.
  *
  * @param mixed $oldversion
  *
  * @return bool
  */
function xmldb_local_stoodle_upgrade($oldversion): bool {
    global $CFG, $DB;
    $dbman = $DB->get_manager();
    if ($oldversion < 2024061408) {
        // Define field test to be added to flashcard_test.
        $table = new xmldb_table('flashcard_test');
        $field = new xmldb_field('test', XMLDB_TYPE_INTEGER, '1', null, null, null, null, 'flashcard_answer');
        // Conditionally launch add field test.
        if (!$dbman->field_exists($table, $field)) {
            $dbman->add_field($table, $field);
        }
        // Stoodle savepoint reached.
        upgrade_plugin_savepoint(true, 2024061408, 'local', 'stoodle');
    }
    return true;
}
