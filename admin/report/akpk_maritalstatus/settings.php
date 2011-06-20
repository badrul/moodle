<?php

defined('MOODLE_INTERNAL') || die;

// just a link to course report
$ADMIN->add('akpkreports', new admin_externalpage('maritalstatus', get_string('maritalstatus', 'admin'), "$CFG->wwwroot/admin/report/akpk_maritalstatus/index.php"));

