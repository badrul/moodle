<?php

defined('MOODLE_INTERNAL') || die;

// just a link to course report
$ADMIN->add('akpkreports', new admin_externalpage('gender', get_string('gender', 'admin'), "$CFG->wwwroot/admin/report/akpk_gender/index.php"));

