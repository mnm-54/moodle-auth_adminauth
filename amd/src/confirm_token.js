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
 * This module is responsible for admin authentication content in the confirm modal.
 *
 * @module     paygw_adminauth/confirm_token
 * @copyright  2021 Brain station 23 ltd.
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

define([
  "jquery",
  "core/ajax",
  "core/str",
  "core/modal_factory",
  "core/modal_events",
  "core/notification",
], function ($, Ajax, Notification) {
  $(".btn-input").on("click", function () {
    console.log("processing");
    password = document.getElementById("password").value;
    token = document.getElementById("token").value;
    user = document.getElementById("username").innerHTML;

    let wsfunction = "adminauth_token_password_verification";
    let params = {
      username: user,
      password: password,
      token: token,
    };
    let request = {
      methodname: wsfunction,
      args: params,
    };

    Ajax.call([request])[0]
      .done(function () {
        window.location.href = $(location).attr("href");
      })
      .fail(Notification.exception);
  });
});
