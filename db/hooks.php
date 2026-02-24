<?php
defined('MOODLE_INTERNAL') || die();

$hooks = [
    [
        'hook' => \core\hook\output\before_http_headers::class,
        'callback' => [\local_customreg\hook_handler::class, 'before_http_headers'],
    ],
    [
        'hook' => \core\hook\user\after_signup::class,
        'callback' => [\local_customreg\hook_handler::class, 'after_signup'],
    ],
    [
        'hook' => \core\hook\user\signup_form_definition::class,
        'callback' => [\local_customreg\hook_handler::class, 'signup_form_definition'],
    ],
];
