<?php

defined('MOODLE_INTERNAL') || die;

// just a link to course report
$ADMIN->add('akpkreports', new admin_externalpage('income', get_string('income', 'admin'), "$CFG->wwwroot/admin/report/akpk_monthlyincome/index.php"));

