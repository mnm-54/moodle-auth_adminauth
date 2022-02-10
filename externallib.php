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
 * web service functions
 *
 * @package    auth_adminauth
 * @copyright  2021 Brain Station 23 ltd
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

require_once("$CFG->libdir/externallib.php");
require_once(__DIR__ . '/../../config.php');
require_once($CFG->libdir . '/lib/moodlelib.php');

class auth_adminauth_login_verification extends external_api
{
    public static function login_verification_parameters()
    {
        return new external_function_parameters(
            array(
                'username' => new external_value(PARAM_INT, "Username"),
                'password' => new external_value(PARAM_INT, "Password"),
                'token' => new external_value(PARAM_INT, "token")
            )
        );
    }
    public static function login_verification($username, $password, $token)
    {
        global $DB;
        if ($token == "12345") {
            $status = authenticate_user_login($username, $password);
            // die(var_dump($status));
            $return_value = [
                'user' => $username,
                'status' => 'processing'
            ];
            return $return_value;
        }
        $return_value = [
            'user' => $username,
            'status' => 'token didnt match'
        ];
        return $return_value;
    }
    public static function login_verification_returns()
    {
        return new external_single_structure(
            array(
                'user' => new external_value(PARAM_INT, 'username'),
                'status' => new external_value(PARAM_TEXT, 'status of user')
            )
        );
    }
}