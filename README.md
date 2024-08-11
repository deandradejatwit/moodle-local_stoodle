# Stoodle #

Stoodle is a plugin for Moodle, an open-source learning management system, that can be utilized by any users of Moodle, including students and teachers. Stoodle allows students to study for their courses directly through their course management system without having to use a third party website. 

Stoodle includes two primary studying features: flashcards and quizzes. Flashcards are two-sided note cards, including a prompt on one side and the answer or information regarding the prompt on the other. Within this feature, users are displayed one side of a flashcard at a time starting with the prompt side, allowing them to test and improve their memory on their desired subject. Additionally, users have the ability to create, edit and delete sets of flashcards. Quizzes are short, informal tests of knowledge. Similar to the flashcard feature, users can create, edit and delete quizzes they have made. Stoodle's quiz feature allows for the creation of numerous question types such as mutliple choice, select all that apply, true or false and open response.

## Installing via uploaded ZIP file ##

1. Log in to your Moodle site as an admin and go to _Site administration >
   Plugins > Install plugins_.
2. Upload the ZIP file with the plugin code. You should only be prompted to add
   extra details if your plugin type is not automatically detected.
3. Check the plugin validation report and finish the installation.

## Installing manually ##

The plugin can be also installed by putting the contents of this directory to

    {your/moodle/dirroot}/local/stoodle

Afterwards, log in to your Moodle site as an admin and go to _Site administration >
Notifications_ to complete the installation.

Alternatively, you can run

    $ php admin/cli/upgrade.php

to complete the installation from the command line.

## License ##

2024 Jonathan Kong-Shi kongshij@wit.edu, Myles R. Sullivan sullivanm22@wit.edu, Jhonathan Deandrade deandradej@wit.edu

This program is free software: you can redistribute it and/or modify it under
the terms of the GNU General Public License as published by the Free Software
Foundation, either version 3 of the License, or (at your option) any later
version.

This program is distributed in the hope that it will be useful, but WITHOUT ANY
WARRANTY; without even the implied warranty of MERCHANTABILITY or FITNESS FOR A
PARTICULAR PURPOSE.  See the GNU General Public License for more details.

You should have received a copy of the GNU General Public License along with
this program.  If not, see <https://www.gnu.org/licenses/>.
