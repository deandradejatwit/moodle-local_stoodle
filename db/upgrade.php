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
    if ($oldversion < 2024061603) {
        // Define table local_stoodle to be created.
        $table = new xmldb_table('local_stoodle');
        // Adding fields to table local_stoodle.
        $table->add_field('id', XMLDB_TYPE_INTEGER, '10', null, XMLDB_NOTNULL, XMLDB_SEQUENCE, null);
        // Adding keys to table local_stoodle.
        $table->add_key('primary', XMLDB_KEY_PRIMARY, ['id']);
        // Conditionally launch create table for local_stoodle.
        if (!$dbman->table_exists($table)) {
            $dbman->create_table($table);
        }
        // Define table flashcard_test to be created.
        $table = new xmldb_table('flashcard_test');
        // Adding fields to table flashcard_test.
        $table->add_field('id', XMLDB_TYPE_INTEGER, '10', null, XMLDB_NOTNULL, XMLDB_SEQUENCE, null);
        $table->add_field('flashcard_question', XMLDB_TYPE_TEXT, null, null, XMLDB_NOTNULL, null, null);
        $table->add_field('flashcard_answer', XMLDB_TYPE_CHAR, '258', null, XMLDB_NOTNULL, null, null);
        $table->add_field('test', XMLDB_TYPE_INTEGER, '1', null, null, null, null);
        // Adding keys to table flashcard_test.
        $table->add_key('primary', XMLDB_KEY_PRIMARY, ['id']);
        // Conditionally launch create table for flashcard_test.
        if (!$dbman->table_exists($table)) {
            $dbman->create_table($table);
        }
        // Define table flashcard_set to be created.
        $table = new xmldb_table('flashcard_set');

        // Adding fields to table flashcard_set.
        $table->add_field('id', XMLDB_TYPE_INTEGER, '10', null, XMLDB_NOTNULL, XMLDB_SEQUENCE, null);
        $table->add_field('set_name', XMLDB_TYPE_TEXT, null, null, XMLDB_NOTNULL, null, null);
        $table->add_field('timemodified', XMLDB_TYPE_INTEGER, '10', null, XMLDB_NOTNULL, null, '0');

        // Adding keys to table flashcard_set.
        $table->add_key('primary', XMLDB_KEY_PRIMARY, ['id']);

        // Conditionally launch create table for flashcard_set.
        if (!$dbman->table_exists($table)) {
            $dbman->create_table($table);
        }
        // Define table flashcard_card to be created.
        $table = new xmldb_table('flashcard_card');

        // Adding fields to table flashcard_card.
        $table->add_field('id', XMLDB_TYPE_INTEGER, '10', null, XMLDB_NOTNULL, XMLDB_SEQUENCE, null);
        $table->add_field('flashcard_set', XMLDB_TYPE_INTEGER, '10', null, XMLDB_NOTNULL, null, '0');
        $table->add_field('question', XMLDB_TYPE_TEXT, null, null, XMLDB_NOTNULL, null, null);
        $table->add_field('answer', XMLDB_TYPE_TEXT, null, null, XMLDB_NOTNULL, null, null);
        $table->add_field('timemodified', XMLDB_TYPE_INTEGER, '10', null, XMLDB_NOTNULL, null, '0');

        // Adding keys to table flashcard_card.
        $table->add_key('primary', XMLDB_KEY_PRIMARY, ['id']);
        $table->add_key('foreign', XMLDB_KEY_FOREIGN, ['flashcard_set'], 'flashcard_set', ['id']);

        // Conditionally launch create table for flashcard_card.
        if (!$dbman->table_exists($table)) {
            $dbman->create_table($table);
        }
        // Stoodle savepoint reached.
        upgrade_plugin_savepoint(true, 2024061603, 'local', 'stoodle');
    }
    return true;
}
