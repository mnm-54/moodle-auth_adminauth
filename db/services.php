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
 * web services
 *
 * @package    auth_adminauth
 * @copyright  2021 Brain Station 23 ltd
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die();

$functions = array(
    'auth_adminauth_token_password_verification' => array(
        'classname' => 'auth_adminauth_login_verification',
        'methodname'  => 'login_verification',
        'classpath'   => 'auth/adminauth/externallib.php',
        'description' => 'Check admin user for its token and password',
        'type'        => 'write',
        'ajax' => true,
    )
);

$services = array(
    'auth_adminauth_verification' => array(
        'functions' => array('auth_adminauth_token_password_verification'),
        'restrictedusers' => 0,
        // into the administration
        'enabled' => 1,
        'shortname' =>  'admin_auth_func',
    )
);