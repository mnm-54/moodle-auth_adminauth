<?php
require_once(__DIR__ . '/../../config.php');
require_once($CFG->libdir . "/moodlelib.php");

global $PAGE, $OUTPUT, $DB;

$username = optional_param('username', 'null', PARAM_TEXT);
$password = optional_param('password', 'null', PARAM_TEXT);
$token = optional_param('token', 'null', PARAM_TEXT);

$templetecontext = (object)[
    'username' => $username,
    'password' => $password,
    // 'redirrecturl' => $CFG->wwwroot . '/auth/adminauth/checktoken.php'
];
if ($token == 'null') {
    echo $OUTPUT->header();
    echo $OUTPUT->render_from_template('auth_adminauth/view', $templetecontext);
    echo $OUTPUT->footer();
} else {
    if ($token == '12345') {
        $ustat = $DB->get_record('auth_adminauth', array('username' => $username));
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
