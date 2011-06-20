<?php

defined('MOODLE_INTERNAL') || die;

// just a link to course report
$ADMIN->add('akpkreports', new admin_externalpage('age', get_string('age', 'admin'), "$CFG->wwwroot/admin/report/akpk_age/index.php"));

