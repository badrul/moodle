<?php

// This file is part of Moodle - http://moodle.org/
//
// Moodle is free software: you can redistribute it and/or modify
// it under the terms of the GNU General Public License as published by
// the Free Software Foundation, either version 3 of the License, or
// (at your option) any later version.
//
// Moodle is distributed in the hope that it will be useful,
// but WITHOUT ANY WARRANTY; without even the implied warranty of
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
// GNU General Public License for more details.
//
// You should have received a copy of the GNU General Public License
// along with Moodle.  If not, see <http://www.gnu.org/licenses/>.

/**
 * user signup page.
 *
 * @package    core
 * @subpackage auth
 * @copyright  1999 onwards Martin Dougiamas  http://dougiamas.com
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

require('../config.php');
require_once('signup_form.php');


if (empty($CFG->registerauth)) {
    print_error('notlocalisederrormessage', 'error', '', 'Sorry, you may not use this page.');
}
$authplugin = get_auth_plugin($CFG->registerauth);

if (!$authplugin->can_signup()) {
    print_error('notlocalisederrormessage', 'error', '', 'Sorry, you may not use this page.');
}

//HTTPS is required in this page when $CFG->loginhttps enabled
$PAGE->https_required();

$PAGE->set_url('/login/signup.php');
$PAGE->set_context(get_context_instance(CONTEXT_SYSTEM));

$mform_signup = new login_signup_form();

if ($mform_signup->is_cancelled()) {
    redirect(get_login_url());

} else if ($user = $mform_signup->get_data()) {
    $user->confirmed   = 0;
    $user->lang        = current_language();
    $user->firstaccess = time();
    $user->timecreated = time();
    $user->mnethostid  = $CFG->mnet_localhost_id;
    $user->secret      = random_string(15);
    $user->auth        = $CFG->registerauth;

    $authplugin->user_signup($user, true); // prints notice and link to login/index.php
    exit; //never reached
}

// make sure we really are on the https page when https login required
$PAGE->verify_https_required();


$newaccount = get_string('newaccount');
$login      = get_string('login');

$PAGE->navbar->add($login);
$PAGE->navbar->add($newaccount);

$PAGE->set_title($newaccount);
$PAGE->set_heading($SITE->fullname);

echo $OUTPUT->header();
echo "<script>

function detectdob(){
	username=document.getElementById('id_username');
	
	if(username.value.length==12){
		document.getElementsByName('profile_field_dob[day]')[0].selectedIndex=username.value.charAt(4)+username.value.charAt(5)-1;
		document.getElementsByName('profile_field_dob[month]')[0].selectedIndex=username.value.charAt(2)+username.value.charAt(3)-1;
		document.getElementsByName('profile_field_dob[year]')[0].selectedIndex=username.value.charAt(0)+username.value.charAt(1)-11;	
		
		if(username.value.charAt(11)%2){
			document.getElementsByName('profile_field_Gender')[0].selectedIndex=1;	
		}else{
			document.getElementsByName('profile_field_Gender')[0].selectedIndex=2;	
		}
	}

}

</script>";
echo get_String('signupcontent');
$mform_signup->display();
echo $OUTPUT->footer();
