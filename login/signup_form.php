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
 * User sign-up form.
 *
 * @package    core
 * @subpackage auth
 * @copyright  1999 onwards Martin Dougiamas  http://dougiamas.com
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die();

require_once($CFG->libdir.'/formslib.php');
require_once($CFG->dirroot.'/user/profile/lib.php');

class login_signup_form extends moodleform {
    function definition() {
        global $USER, $CFG;

        $mform = $this->_form;

        $mform->addElement('header', '', get_string('createuserandpass'), '');


        $mform->addElement('text', 'username', get_string('username1'), 'maxlength="100" size="12" onChange="detectdob();"');
        $mform->setType('username', PARAM_NOTAGS);
        $mform->addRule('username', get_string('missingusername'), 'required', null, 'server');
		$mform->addRule('username', get_string('err_numeric','form'), 'numeric', null, 'server');

        if (!empty($CFG->passwordpolicy)){
            $mform->addElement('static', 'passwordpolicyinfo', '', print_password_policy());
        }
        $mform->addElement('passwordunmask', 'password', get_string('password'), 'maxlength="32" size="12"');
        $mform->setType('password', PARAM_RAW);
        $mform->addRule('password', get_string('missingpassword'), 'required', null, 'server');

        $mform->addElement('header', '', get_string('supplyinfo'),'');

        $mform->addElement('text', 'email', get_string('email'), 'maxlength="100" size="25"');
        $mform->setType('email', PARAM_NOTAGS);
        $mform->addRule('email', get_string('missingemail'), 'required', null, 'server');

        $mform->addElement('text', 'email2', get_string('emailagain'), 'maxlength="100" size="25"');
        $mform->setType('email2', PARAM_NOTAGS);
        $mform->addRule('email2', get_string('missingemail'), 'required', null, 'server');

        $nameordercheck = new stdClass();
        $nameordercheck->firstname = 'a';
        //$nameordercheck->lastname  = 'b';
        if (fullname($nameordercheck) == 'b a' ) {  // See MDL-4325
            //$mform->addElement('text', 'lastname',  get_string('lastname'),  'maxlength="100" size="30"');
            $mform->addElement('text', 'firstname', get_string('firstname'), 'maxlength="100" size="30"');
        } else {
            $mform->addElement('text', 'firstname', get_string('firstname'), 'maxlength="100" size="30"');
            $mform->addElement('hidden', 'lastname',  '&nbsp;');
        }

        $mform->setType('firstname', PARAM_TEXT);
        $mform->addRule('firstname', get_string('missingfirstname'), 'required', null, 'server');

        //$mform->setType('lastname', PARAM_TEXT);
        //$mform->addRule('lastname', get_string('missinglastname'), 'required', null, 'server');
/*
        $mform->addElement('text', 'city', get_string('city'), 'maxlength="120" size="20"');
        $mform->setType('city', PARAM_TEXT);
        $mform->addRule('city', get_string('missingcity'), 'required', null, 'server');
        if (!empty($CFG->defaultcity)) {
            $mform->setDefault('city', $CFG->defaultcity);
        }

        $country = get_string_manager()->get_list_of_countries();
        $default_country[''] = get_string('selectacountry');
        $country = array_merge($default_country, $country);
        $mform->addElement('select', 'country', get_string('country'), $country);
        $mform->addRule('country', get_string('missingcountry'), 'required', null, 'server');

        if( !empty($CFG->country) ){
            $mform->setDefault('country', $CFG->country);
        }else{
            $mform->setDefault('country', '');
        }

        if ($this->signup_captcha_enabled()) {
            $mform->addElement('recaptcha', 'recaptcha_element', get_string('recaptcha', 'auth'), array('https' => $CFG->loginhttps));
            $mform->addHelpButton('recaptcha_element', 'recaptcha', 'auth');
        }
	*/	
        $this->profile_signup_fields_modified($mform);

        if (!empty($CFG->sitepolicy)) {
            $mform->addElement('header', '', get_string('policyagreement'), '');
            $mform->addElement('static', 'policylink', '', '<a href="'.$CFG->sitepolicy.'" onclick="this.target=\'_blank\'">'.get_String('policyagreementclick').'</a>');
            $mform->addElement('checkbox', 'policyagreed', get_string('policyaccept'));
            $mform->addRule('policyagreed', get_string('policyagree'), 'required', null, 'server');
        }

        // buttons
        $this->add_action_buttons(true, get_string('createaccount'));

    }

    function definition_after_data(){
        $mform = $this->_form;
        $mform->applyFilter('username', 'trim');
    }

    function validation($data, $files) {
        global $CFG, $DB;
        $errors = parent::validation($data, $files);

        $authplugin = get_auth_plugin($CFG->registerauth);

        if ($DB->record_exists('user', array('username'=>$data['username'], 'mnethostid'=>$CFG->mnet_localhost_id))) {
            $errors['username'] = get_string('usernameexists');
        } else {
            //check allowed characters
            if ($data['username'] !== moodle_strtolower($data['username'])) {
                $errors['username'] = get_string('usernamelowercase');
            } else {
                if ($data['username'] !== clean_param($data['username'], PARAM_USERNAME)) {
                    $errors['username'] = get_string('invalidusername');
                }

            }
        }

        //check if user exists in external db
        //TODO: maybe we should check all enabled plugins instead
        if ($authplugin->user_exists($data['username'])) {
            $errors['username'] = get_string('usernameexists');
        }


        if (! validate_email($data['email'])) {
            $errors['email'] = get_string('invalidemail');

        } else if ($DB->record_exists('user', array('email'=>$data['email']))) {
            $errors['email'] = get_string('emailexists').' <a href="forgot_password.php">'.get_string('newpassword').'?</a>';
        }
        if (empty($data['email2'])) {
            $errors['email2'] = get_string('missingemail');

        } else if ($data['email2'] != $data['email']) {
            $errors['email2'] = get_string('invalidemail');
        }
        if (!isset($errors['email'])) {
            if ($err = email_is_not_allowed($data['email'])) {
                $errors['email'] = $err;
            }

        }

        $errmsg = '';
        if (!check_password_policy($data['password'], $errmsg)) {
            $errors['password'] = $errmsg;
        }

        if ($this->signup_captcha_enabled()) {
            $recaptcha_element = $this->_form->getElement('recaptcha_element');
            if (!empty($this->_form->_submitValues['recaptcha_challenge_field'])) {
                $challenge_field = $this->_form->_submitValues['recaptcha_challenge_field'];
                $response_field = $this->_form->_submitValues['recaptcha_response_field'];
                if (true !== ($result = $recaptcha_element->verify($challenge_field, $response_field))) {
                    $errors['recaptcha'] = $result;
                }
            } else {
                $errors['recaptcha'] = get_string('missingrecaptchachallengefield');
            }
        }

        return $errors;

    }

    /**
     * Returns whether or not the captcha element is enabled, and the admin settings fulfil its requirements.
     * @return bool
     */
    function signup_captcha_enabled() {
        global $CFG;
        return !empty($CFG->recaptchapublickey) && !empty($CFG->recaptchaprivatekey) && get_config('auth/email', 'recaptcha');
    }
	
	/**
	* Adds code snippet to a moodle form object for custom profile fields that
	* should appear on the signup page
	* @param  object  moodle form object
	*/
	function profile_signup_fields_modified(&$mform) {
		global $CFG, $DB;

		//only retrieve required custom fields (with category information)
		//results are sort by categories, then by fields
		$sql = "SELECT uf.id as fieldid, ic.id as categoryid, ic.name as categoryname, uf.datatype
                FROM {user_info_field} uf
                JOIN {user_info_category} ic
                ON uf.categoryid = ic.id AND uf.signup = 1 AND uf.visible<>0
                ORDER BY ic.sortorder ASC, uf.sortorder ASC";

		if ( $fields = $DB->get_records_sql($sql)) {
			$exceptcategoryname=array('Contact','contact2','Personal Information');
			foreach ($fields as $field) {
				//check if we change the categories
				
				if ((!isset($currentcat) || $currentcat != $field->categoryid)) {
					$currentcat = $field->categoryid;
					
					if($field->categoryname=='contact2'){
						$mform->addElement('text', 'city', get_string('city'), 'maxlength="120" size="20"');
						$mform->setType('city', PARAM_TEXT);
						$mform->addRule('city', get_string('missingcity'), 'required', null, 'server');
						if (!empty($CFG->defaultcity)) {
							$mform->setDefault('city', $CFG->defaultcity);
						}
					}
					
					if($field->categoryname=='Personal Information'){
						$country = get_string_manager()->get_list_of_countries();
						$default_country[''] = get_string('selectacountry');
						$country = array_merge($default_country, $country);
						$mform->addElement('select', 'country', get_string('country'), $country);
						$mform->addRule('country', get_string('missingcountry'), 'required', null, 'server');

						if( !empty($CFG->country) ){
							$mform->setDefault('country', $CFG->country);
						}else{
							$mform->setDefault('country', '');
						}

						if ($this->signup_captcha_enabled()) {
							$mform->addElement('recaptcha', 'recaptcha_element', get_string('recaptcha', 'auth'), array('https' => $CFG->loginhttps));
							$mform->addHelpButton('recaptcha_element', 'recaptcha', 'auth');
						}
					}
					if(!in_array($field->categoryname,$exceptcategoryname)){
						if(strpos($field->categoryname,' ') || $field->categoryname==ucfirst($field->categoryname)){
							$categoryname=strtolower(str_replace(' ','',$field->categoryname));
							$mform->addElement('header', 'category_'.$field->categoryid, get_string($categoryname));
						}else{
							$mform->addElement('header', 'category_'.$field->categoryid, format_string($field->categoryname));
						}
					}
				}
				
				require_once($CFG->dirroot.'/user/profile/field/'.$field->datatype.'/field.class.php');
				$newfield = 'profile_field_'.$field->datatype;
				$formfield = new $newfield($field->fieldid);
				$formfield->edit_field($mform);
				
				
			}
		}
	}

	
}
