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
 * check token form
 *
 * @package    auth_adminauth
 * @copyright  2021 Brain Station 23 ltd
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 * @var stdClass $plugin 
 */


//moodleform is defined in formslib.php
require_once("$CFG->libdir/formslib.php");

class checkform extends moodleform
{
    //Add elements to form
    public function definition()
    {
        global $CFG;

        $mform = $this->_form; // Don't forget the underscore! 

        $mform->addElement('password', 'password', get_string('password', 'auth_adminauth')); // Add elements to your form
        $mform->setType('password', PARAM_TEXT);                   //Set type of element
        $mform->addElement('text', 'token', get_string('token', 'auth_adminauth')); // Add elements to your form
        $mform->setType('token', PARAM_TEXT);                   //Set type of element

        $this->add_action_buttons();
    }
    //Custom validation should be added here
    function validation($data, $files)
    {
        return array();
    }
}
