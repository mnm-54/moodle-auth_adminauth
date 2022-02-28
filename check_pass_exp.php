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
 * check if password of user is expired for 'auth_adminauth' and redirrects it.
 *
 * @package    auth_adminauth
 * @copyright  2021 Brain Station 23 ltd
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

/**
 * Password expiration check
 * Check if password needs to expire and if so
 * expired it and redirect to defined page (default new password page)
 *
 * @param object $user user object, later used for $USER
 * @param string $username (with system magic quotes)
 * @param string $password plain text password (with system magic quotes)
 * 
 */

require_once(__DIR__ . '/../../config.php');
function checkPasswordExpiration(&$user)
{
    global $CFG;
    $config = get_config('auth_adminauth');
    if ($config->passexp == null || $config->passexp == '') {
        $config->passexp = '60';
    }

    $today = mktime(0, 0, 0, date("m"), date("d"), date("Y"));
    // default date to -1 so if not found always before today
    $passwordExpDate = get_user_preferences(PREF_FIELD_AUTH_PWDEXP_DATE, -1, $user->id);
    // If not settings found don't expire otherwise check date
    $passwordExpired = ($passwordExpDate <= $today);
    if ($passwordExpired && ($user->auth == 'manual')) {
        $expirationdays = $config->passexp;

        // force new password change
        set_user_preference('auth_forcepasswordchange', 1, $user->id);

        // set new date
        $newexpdate = mktime(0, 0, 0, date("m"), (date("d") + $expirationdays), date("Y"));
        set_user_preference(PREF_FIELD_AUTH_PWDEXP_DATE, $newexpdate, $user->id);
    }
    return;
}
