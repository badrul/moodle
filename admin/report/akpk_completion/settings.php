<?php

defined('MOODLE_INTERNAL') || die;

// just a link to course report
$ADMIN->add('akpkreports', new admin_externalpage('completion', get_string('completion', 'admin'), "$CFG->wwwroot/admin/report/akpk_completion/index.php"));

