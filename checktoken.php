<?php
require_once(__DIR__ . '/../../config.php');
require_once($CFG->dirroot . '/auth/adminauth/classes/forms/checkform.php');

global $PAGE, $OUTPUT;

$username = optional_param('user', 'null', PARAM_TEXT);

echo $OUTPUT->header();
$templetecontext = (object)[
    'username' => $username
];
echo $OUTPUT->render_from_template('auth_adminauth/view', $templetecontext);

$PAGE->requires->js_call_amd('auth_adminauth/confirm_token', 'init', array());

echo $OUTPUT->footer();
