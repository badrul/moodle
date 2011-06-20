<?php

defined('MOODLE_INTERNAL') || die;

// just a link to course report
$ADMIN->add('akpkreports', new admin_externalpage('user', get_string('userstatistics'), "$CFG->wwwroot/admin/report/akpk_user/index.php"));

