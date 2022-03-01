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
 * Generate otp codes for 'auth_adminauth'.
 *
 * @package    auth_adminauth
 * @copyright  2021 Brain Station 23 ltd
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

require_once(__DIR__ . '/../../config.php');

defined('MOODLE_INTERNAL') || die();

function auth_token($username)
{
    global $DB;
    $otpcode = rand(100000, 999999);
    $userstat = $DB->get_record_select('auth_adminauth', "username = :username", array('username' => $username));
    if (!$userstat) {
        create_user_instance($username, $otpcode);
    } else {
        $userstat->otp = $otpcode;
        $userstat->timevalid = otp_validity();
        $userstat->status = 1;
        $DB->update_record('auth_adminauth', $userstat);
    }

    return $otpcode;
}

function send_otp_mail($userinfo)
{
    global $CFG; // adding otp mail code
    $useremail = new stdClass();
    $useremail->email = $userinfo->email; // for testing example 'testuser@yopmail.com';
    $useremail->id = $userinfo->id;
    $subject = "otp code";
    $messagetxt = auth_token($userinfo->username);
    $sender = new stdClass();
    $sender->email = 'tester@example.com';
    $sender->id = -98;
    // Manage Moodle debugging options.
    $debuglevel = $CFG->debug;
    $debugdisplay = $CFG->debugdisplay;
    $debugsmtp = $CFG->debugsmtp ?? null; // This might not be set as it's optional.
    $CFG->debugdisplay = true;
    $CFG->debugsmtp = true;
    $CFG->debug = 15;
    // send email
    ob_start();
    $success = email_to_user($useremail, $sender, $subject, $messagetxt, '', '', '', false);
    $smtplog = ob_get_contents();
    ob_end_clean();

    return $smtplog;
}

function create_user_instance($username, $otpcode)
{
    global $DB;
    $userstat = new stdClass();
    $userstat->username = $username;
    $userstat->status = 1;
    $userstat->timevalid = otp_validity();
    $userstat->otp = $otpcode;
    $DB->insert_record("auth_adminauth", $userstat);
    return;
}

function otp_validity()
{
    $otpvalidity = time() + 10 * 60;
    return $otpvalidity;
}
