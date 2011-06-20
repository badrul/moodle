<?php

defined('MOODLE_INTERNAL') || die;

// just a link to course report
$ADMIN->add('akpkreports', new admin_externalpage('loan', get_string('loan', 'admin'), "$CFG->wwwroot/admin/report/akpk_loan/index.php"));

