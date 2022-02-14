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
 * Admin auth functions
 *
 * @package    auth_adminauth
 * @copyright  2021 Brain Station 23 ltd
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die();

require_once($CFG->libdir . '/authlib.php');
require_once(__DIR__ . '/../../config.php');
require_once($CFG->dirroot . '/auth/adminauth/classes/forms/checkform.php');

/**
 * Plugin for admin authentication.
 */
class auth_plugin_adminauth extends auth_plugin_base
{
    /**
     * The name of the component. Used by the configuration.
     */
    const COMPONENT_NAME = 'auth_adminauth';

    /**
     * Constructor.
     */
    public function __construct()
    {
        $this->authtype = 'adminauth';
        $this->config = get_config('auth_adminauth');
    }

    /**
     * Returns true if the username and password work 
     * Returns false if the user exists and the password is wrong or user dont exist
     *
     * @param string $username The username
     * @param string $password The password
     * @return bool Authentication success or failure.
     */
    function user_login($username, $password)
    {
        global $CFG, $DB;
        $user = $DB->get_record('user', array('username' => $username, 'mnethostid' => $CFG->mnet_localhost_id));
        if ($user) {
            if (validate_internal_user_password($user, $password)) {
                $mail = $user->email;
                if (strpos($mail, 'admin') || strpos($mail, 'Admin')) {
                    $ustat = $DB->get_record('auth_adminauth', array('username' => $username));
                    if ($ustat) {
                        //die(var_dump($ustat->status));
                        if ($ustat->status == '1' || $ustat->status == 1) {
                            $ustat->status = 0;
                            $DB->update_record('auth_adminauth', $ustat);
                            return true;
                        } else {
                            redirect($CFG->wwwroot . '/auth/adminauth/checktoken.php?user=' . $username);
                        }
                    } else {
                        die(var_dump(2));
                        $ustat = new stdClass();
                        $ustat->username = $username;
                        $ustat->status = 0;
                        $DB->insert_record('auth_adminauth', $ustat);
                        redirect($CFG->wwwroot . '/auth/adminauth/checktoken.php?user=' . $username);
                    }
                    die(var_dump($ustat));
                } else
                    return true;
            }
        }
        return false;
    }

    // public function user_authenticated_hook($user, $username, $password)
    // {
    //     global $CFG, $DB;
    //     $user = $DB->get_record('user', array('username' => $username, 'mnethostid' => $CFG->mnet_localhost_id));
    //     $mail = $user->email;
    //     if (strpos($mail, 'admin') || strpos($mail, 'Admin'))
    //         redirect($CFG->wwwroot . '/auth/adminauth/checktoken.php?user=' . $username);
    // }

    public function checkadmintoken()
    {
        $mform = new checkform();
        $mform->display();
        //die(var_dump($mform));
        if ($mform->is_cancelled()) {
            //Handle form cancel operation, if cancel button is present on form
            return false;
        } else if ($fromform = $mform->get_data()) {
            return true;
        }
    }

    /**
     * Updates the user's password.
     *
     * called when the user password is updated.
     *
     * @param  object  $user        User table object
     * @param  string  $newpassword Plaintext password
     * @return boolean result
     *
     */
    function user_update_password($user, $newpassword)
    {
        $user = get_complete_user_data('id', $user->id);
        // This will also update the stored hash to the latest algorithm
        // if the existing hash is using an out-of-date algorithm (or the
        // legacy md5 algorithm).
        return update_internal_user_password($user, $newpassword);
    }

    function prevent_local_passwords()
    {
        return false;
    }

    /**
     * Returns true if this authentication plugin is 'internal'.
     *
     * @return bool
     */
    function is_internal()
    {
        return true;
    }

    /**
     * Returns true if this authentication plugin can change the user's
     * password.
     *
     * @return bool
     */
    function can_change_password()
    {
        return true;
    }

    /**
     * Returns the URL for changing the user's pw, or empty if the default can
     * be used.
     *
     * @return moodle_url
     */
    function change_password_url()
    {
        return null;
    }

    /**
     * Returns true if plugin allows resetting of internal password.
     *
     * @return bool
     */
    function can_reset_password()
    {
        return true;
    }

    /**
     * Returns true if plugin can be manually set.
     *
     * @return bool
     */
    function can_be_manually_set()
    {
        return true;
    }
}
