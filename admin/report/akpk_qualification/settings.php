<?php

defined('MOODLE_INTERNAL') || die;

// just a link to course report
$ADMIN->add('akpkreports', new admin_externalpage('qualification', get_string('qualification', 'admin'), "$CFG->wwwroot/admin/report/akpk_qualification/index.php"));

