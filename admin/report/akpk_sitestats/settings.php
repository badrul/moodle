<?php

defined('MOODLE_INTERNAL') || die;

// just a link to course report
$ADMIN->add('akpkreports', new admin_externalpage('sitestats', get_string('sitestats', 'admin'), "$CFG->wwwroot/admin/report/akpk_sitestats/index.php"));

