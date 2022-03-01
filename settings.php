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
 * Admin auth settings and defaults.
 *
 * @package    auth_adminauth
 * @copyright  2021 Brain Station 23 ltd
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die();

if ($ADMIN->fulltree) {
    // Introductory explanation.
    $settings->add(new admin_setting_heading(
        'auth_adminauth/pluginname',
        '',
        new lang_string('auth_adminauthdescription', 'auth_adminauth')
    ));
    $settings->add(new admin_setting_configtext(
        'auth_adminauth/passexp',
        get_string('passexp/visiblename', 'auth_adminauth'),
        get_string('passexp/description', 'auth_adminauth'),
        60
    ));
    // $settings->add(new admin_setting_configtext(
    //     'auth_adminauth/tokenvalidity',
    //     get_string('tokenvalidity/visiblename', 'auth_adminauth'),
    //     get_string('tokenvalidity/description', 'auth_adminauth'),
    //     2
    // ));

    // Display locking / mapping of profile fields.
    $authplugin = get_auth_plugin('adminauth');
}
