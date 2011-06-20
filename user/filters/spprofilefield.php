<?php

require_once($CFG->dirroot.'/user/filters/lib.php');
require_once($CFG->dirroot.'/user/profile/lib.php');
/**
 * User filter based on values of custom profile fields.
 */
class user_filter_spprofilefield extends user_filter_type {
	
    /**
     * Constructor
     * @param string $name the name of the filter instance
     * @param string $label the label of the filter instance
     * @param boolean $advanced advanced form element flag
     */
    function user_filter_spprofilefield($name, $label, $advanced) {
        parent::user_filter_type($name, $label, $advanced);
    }

    /**
     * Returns an array of comparison operators
     * @return array of comparison operators
     */
    function get_operators($type=null) {
		if($type=='menu'){
			return array(
				1 => get_string('doesnotcontain','filters'),
				2 => get_string('isequalto','filters')
			);
                
		}elseif($type=='menuloan'){
			return array(
				5 => 'does not take',
				6 => 'had taken'
			);
                
		}
		if($type=='age'){
			return array(
				2 => get_string('isequalto','filters'),
				3 => 'less than',
				4 => 'more than'
			);
		}

    }
	
	function processName(){
		$process=array(
			'Marital Status'=>'MaritalStatus',
			'Loan Taken'=>'LoanTaken',
			'Monthly Income'=>'MonthlyIncome'
		);
		if(in_array($this->_name,array_keys($process))){
			$this->_name=$process[$this->_name];
		}
	}
	
	function oriName(){
		$process=array(
			'Marital Status'=>'MaritalStatus',
			'Age'=>'FLOOR(DATEDIFF(NOW(), FROM_UNIXTIME(DOB)) / (365))',
			'Loan Taken'=>'LoanTaken',
			'Monthly Income'=>'MonthlyIncome'
		);
		if(array_search($this->_name,$process)){
			$this->_name=array_search($this->_name,$process);
		}
	}
	
	function getField(){
		global $DB;
		
		$this->oriName();
	
		if($this->_name=='Age'){
			$sql = "SELECT uf.id as id, uf.datatype,uf.param1 as options FROM {user_info_field} uf where uf.name='DOB'";
			$fields = $DB->get_records_sql($sql);
			foreach($fields as $field){}
			$field->datatype='age';
			return $field;
		}elseif($this->_name=='Loan Taken'){
			$field->datatype='menuloan';
			$field->options=array(
				'Choose...',
				'Personal Loan',
				'Education Loan',
				'Housing Loan',
				'Credit Card',
				'Car Loan',
				'Others'
			);
			return $field;
		}else{
			$sql = "SELECT uf.id as id, uf.datatype,uf.param1 as options FROM {user_info_field} uf where uf.name='$this->_name'";
			
			$fields = $DB->get_records_sql($sql);
			foreach($fields as $field){
				$field->options = array_merge(array('Choose...'),explode("\n", $field->options));
				return $field;
			}
		}
			
	}

    /**
     * Adds controls specific to this filter in the form.
     * @param object $mform a MoodleForm object to setup
     */
    function setupForm(&$mform) {
		$field=$this->getField();
		$this->processName();
		
        $objs = array();
        $objs[] =&$mform->createElement('select', $this->_name.'_op', null, $this->get_operators($field->datatype));
        
		if(in_array($field->datatype,array('menu','menuloan'))){
			$objs[]=$mform->createElement('select', $this->_name.'_fld', null, $field->options);
		}else{
			$objs[] =& $mform->createElement('text', $this->_name.'_fld', null);
		}
        $grp =& $mform->addElement('group', $this->_name.'_grp', $this->_label, $objs, '', false);
        if ($this->_advanced) {
            $mform->setAdvanced($this->_name.'_grp');
        
		}
    }

    /**
     * Retrieves data from the form data
     * @param object $formdata data submited with the form
     * @return mixed array filter data or false when filter not set
     */
    function check_data($formdata) {
		$field    = $this->_name;
        $operator = $field.'_op';
        $profile  = $field.'_fld';

        if (array_key_exists($profile, $formdata)) {
               return array('value'    => (string)$field,
                         'operator' => (int)$formdata->$operator,
                         'profile'  => (int)$formdata->$profile);
        }
    }

    /**
     * Returns the condition to be used with SQL where
     * @param array $data filter settings
     * @return array sql string and $params
     */
    function get_sql_filter($data) {
        global $DB;
        static $counter = 0;
        $name = 'ex_profilefield'.$counter++;

		if($data['profile']){
			$profile  = $data['profile'];
			$operator = $data['operator'];
			$value    = $data['value'];
			
			$field=$this->getField();
			if(in_array($field->datatype,array('menu','menuloan'))){
				$options=$field->options;
				$profile=$options[$profile];
			}
			
			$params = array();
		
        switch($operator) {

            case 1: // does not contain
                $symbol = '!=';
                $params[$name] = "%$value%";
                break;
            case 2: // equal to
                $symbol = '=';
                $params[$name] = "$value";
                break;
            case 3: // less than
                $symbol = '<';
                $params[$name] = "$value%";
                break;
            case 4: // more than
				$symbol = '>';
                $params[$name] = "%$value";
                break;
			case 5: // does not take
                $symbol = '!=';
                $params[$name] = "%$value%";
                break;
            case 6: // had taken
                $symbol = '=';
                $params[$name] = "$value";
                break;
        }
		$this->processName();
		if($this->_name=='Age'){
			$this->_name='FLOOR(DATEDIFF(NOW(), FROM_UNIXTIME(DOB)) / (365))';
		}elseif($this->_name=='LoanTaken'){
			$profile=str_replace(' ','',$profile);
			return array("$profile $symbol 1",$params);
		}
		return array("$this->_name $symbol '$profile'",$params);
		}
		return array('',array());
    }

    /**
     * Returns a human friendly description of the filter used as label.
     * @param array $data filter settings
     * @return string active filter label
     */
    function get_label($data) {
	if($data['profile']){
		global $DB;
        //$operators = $this->get_operators();
        $profile  = $data['profile'];
        $operator = $data['operator'];
        $value    = $data['value'];
		
		$field=$this->getField();
			if(in_array($field->datatype,array('menu','menuloan'))){
				$options=$field->options;
				$profile=$options[$profile];
			}
		
        switch($operator) {
            case 1: return "$this->_name doesn't contain $profile";
            case 2: return "$this->_name equal to $profile";
            case 3: return "$this->_name less than $profile";
            case 4: return "$this->_name more than $profile";
            case 5: return "Does not take $profile";
            case 6: return "Had taken $profile";
        }
    }
	}
}
