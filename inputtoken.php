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
 * Admin auth input token functions
 *
 * @package    auth_adminauth
 * @copyright  2021 Brain Station 23 ltd
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

require_once(__DIR__ . '/../../config.php');
require_once($CFG->libdir . "/moodlelib.php");
require_once(__DIR__ . '/generate_otp_code.php');

global $PAGE, $OUTPUT, $DB;

$PAGE->set_url(new moodle_url('/auth/adminauth/inputtoken.php'));
$PAGE->set_context(\context_system::instance());
$PAGE->set_title(get_string('title_token_page', 'auth_adminauth'));

$username = optional_param('username', 'null', PARAM_TEXT);
$password = optional_param('password', 'null', PARAM_TEXT);
$token = optional_param('token', 'null', PARAM_TEXT);


$userinfo = $DB->get_record('user', array('username' => $username, 'mnethostid' => $CFG->mnet_localhost_id));

$templetecontext = (object)[
    'username' => $username,
    'password' => $password,
];
if ($token == 'null') {
    // adding otp mail code
    $useremail = new stdClass();
    $useremail->email = $userinfo->email; // for testing example 'testuser@yopmail.com';
    $useremail->id = $userinfo->id;
    $subject = "otp code";
    $messagetxt = auth_token($username);
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

    echo $OUTPUT->header();
    echo $OUTPUT->render_from_template('auth_adminauth/view', $templetecontext);
    echo $OUTPUT->footer();
} else {
    $userstat = $DB->get_record_select('auth_adminauth', "username = :username", array('username' => $username));
    if ($userstat->otp == $token) {
        $ustat = $DB->get_record_select('auth_adminauth', "username = :username", array('username' => $username));
        $ustat->status = 1;
        $DB->update_record('auth_adminauth', $ustat);
        $user = authenticate_user_login($username, $password);
        if ($user) {
            complete_user_login($user);
            redirect($CFG->wwwroot, "login successful", null, \core\output\notification::NOTIFY_SUCCESS);
        } else {
            redirect($CFG->wwwroot . "/login/index.php", "login failed", null, \core\output\notification::NOTIFY_ERROR);
        }
    } else {
        redirect($CFG->wwwroot . "/login/index.php", "login failed, wrong token", null, \core\output\notification::NOTIFY_ERROR);
    }
}
