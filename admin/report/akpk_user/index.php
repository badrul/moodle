<?php
require(dirname(dirname(dirname(dirname(__FILE__)))).'/config.php');
require_once($CFG->libdir.'/adminlib.php');
require_once($CFG->dirroot.'/admin/report/filter.php');

 admin_externalpage_setup('user');
 echo $OUTPUT->header();

// page parameters
$page    = optional_param('page', 0, PARAM_INT);
$perpage = optional_param('perpage', 30, PARAM_INT);    // how many per page
$sort    = optional_param('sort', 'timemodified', PARAM_ALPHA);
$dir     = optional_param('dir', 'DESC', PARAM_ALPHA);
/*
$load=(optional_param('loan', '', PARAM_ALPHA)=='on')?1:0;
$age=(optional_param('age', '', PARAM_ALPHA)=='on')?1:0;
$gen=(optional_param('gender', '', PARAM_ALPHA)=='on')?1:0;
$marital=(optional_param('marital', '', PARAM_ALPHA)=='on')?1:0;
$income=(optional_param('income', '', PARAM_ALPHA)=='on')?1:0;
$qua=(optional_param('qualification', '', PARAM_ALPHA)=='on')?1:0;*/
$baseurl=new moodle_url('/admin/report/akpk_user/index.php');

$pa=array('load'=>'loan','age'=>'age','gen'=>'gender','marital'=>'marital','income'=>'income','qua'=>'qualification');
$params='&';
//$params=array();
foreach($pa as $p=>$param){
	if(optional_param($param, '', PARAM_ALPHA)=='on'){
		${$p}=1;
		$params.="&$param=on";
		//$params[].=array($param=>'on');
	}else{
		${$p}=0;
	}
	
}
$params=str_replace('&&','?',$params);
if(strpos($params,'=')){
$baseurl.=$params;
}
$ufiltering = new filter(array('Age'=>0,'Gender'=>0,'Qualification'=>0,'Marital Status'=>0,'Loan Taken'=>0,'Monthly Income'=>0,'lastlogin'=>0),$baseurl);
list($extrasql, $params) = $ufiltering->get_sql_filter();
	$ufiltering->display_add();
	$ufiltering->display_active();
?>
<FORM ACTION="<?php echo $baseurl;?>">
	<INPUT TYPE=CHECKBOX NAME="age" <?php echo ($age==1)?'checked="checked"':"";?>>Age<BR>
	<INPUT TYPE=CHECKBOX NAME="gender" <?php echo ($gen==1)?'checked="checked"':"";?>>Gender<BR>
	<INPUT TYPE=CHECKBOX NAME="loan" <?php echo ($load==1)?'checked="checked"':"";?>>Loan taken<BR>
	<INPUT TYPE=CHECKBOX NAME="marital" <?php echo ($marital==1)?'checked="checked"':"";?>>Marital Status<BR>
	<INPUT TYPE=CHECKBOX NAME="income" <?php echo ($income==1)?'checked="checked"':"";?>>Monthly Income<BR>
	<INPUT TYPE=CHECKBOX NAME="qualification" <?php echo ($qua==1)?'checked="checked"':"";?>>Qualification<BR>
	<INPUT TYPE=SUBMIT VALUE="submit">
</FORM>
<br/>
<?php
//if got tick, load the library	
if($load || $age || $gen || $marital || $income ||$qua){
?>
<script type="text/javascript" src="https://www.google.com/jsapi"></script>
<script type="text/javascript">
  // Load the Visualization API and the piechart package.
  google.load('visualization', '1', {'packages':['corechart']});
</script>
<?php
}
?>

<?php
if($age==1){
$sql = "SELECT FLOOR(DATEDIFF(NOW(), FROM_UNIXTIME(d.data)) / (365 * 5)) AS age_group,
			DATE_FORMAT( FROM_DAYS( DATEDIFF( NOW( ) , FROM_UNIXTIME( d.data ) ) ) , '%Y' ) +0 AS age,
			a.data AS gender,
			COUNT(*) AS total
		FROM user_info_data d, user_info_data a,
			user_info_field f, user_info_field g
		WHERE (d.fieldid = f.id AND f.name = 'DOB') 
			AND (a.fieldid = g.id AND g.name = 'Gender')
			AND (d.userid = a.userid) 
		GROUP BY age_group, gender 
		ORDER BY age_group ASC";
if($extrasql){
	$sql = "SELECT 
				FLOOR(DATEDIFF(NOW(), 
				FROM_UNIXTIME(da.dob)) / (365 * 5)) AS age_group,
				DATE_FORMAT( FROM_DAYS( DATEDIFF( NOW( ) , FROM_UNIXTIME( da.dob ) ) ) , '%Y' ) +0 AS age, 
				da.gender AS gender,
				COUNT(*) AS total
			FROM
				(select 
					`u`.`id` AS `id`,
					`u`.`city` AS `City`,
					(select `c`.`data` from `user_info_data` `c` where ((`c`.`fieldid` = 14) and (`c`.`userid` = `u`.`id`))) AS `State`,
					(select `c`.`data` from `user_info_data` `c` where ((`c`.`fieldid` = 4) and (`c`.`userid` = `u`.`id`))) AS `Gender`,
					(select `c`.`data` from `user_info_data` `c` where ((`c`.`fieldid` = 5) and (`c`.`userid` = `u`.`id`))) AS `MaritalStatus`,
					(select `c`.`data` from `user_info_data` `c` where ((`c`.`fieldid` = 6) and (`c`.`userid` = `u`.`id`))) AS `Qualification`,
					(select `c`.`data` from `user_info_data` `c` where ((`c`.`fieldid` = 7) and (`c`.`userid` = `u`.`id`))) AS `Noofdependants`,
					(select `c`.`data` from `user_info_data` `c` where ((`c`.`fieldid` = 8) and (`c`.`userid` = `u`.`id`))) AS `DOB`,
					(select `c`.`data` from `user_info_data` `c` where ((`c`.`fieldid` = 2) and (`c`.`userid` = `u`.`id`))) AS `Monthlyincome`,
					(select `c`.`data` from `user_info_data` `c` where ((`c`.`fieldid` = 16) and (`c`.`userid` = `u`.`id`))) AS `PersonalLoan`,
					(select `c`.`data` from `user_info_data` `c` where ((`c`.`fieldid` = 19) and (`c`.`userid` = `u`.`id`))) AS `EducationLoan`,
					(select `c`.`data` from `user_info_data` `c` where ((`c`.`fieldid` = 20) and (`c`.`userid` = `u`.`id`))) AS `HousingLoan`,
					(select `c`.`data` from `user_info_data` `c` where ((`c`.`fieldid` = 21) and (`c`.`userid` = `u`.`id`))) AS `CreditCard`,
					(select `c`.`data` from `user_info_data` `c` where ((`c`.`fieldid` = 22) and (`c`.`userid` = `u`.`id`))) AS `CarLoan`,
					(select `c`.`data` from `user_info_data` `c` where ((`c`.`fieldid` = 23) and (`c`.`userid` = `u`.`id`))) AS `Others`,
					`u`.`lastlogin` AS `lastlogin` 
					from `user` `u` 
					where (`u`.`deleted` = 0 ) 
					) da	
			where da.gender!='' AND $extrasql
			GROUP BY age_group, gender 
			ORDER BY age_group ASC
		";
}

$rs = $DB->get_recordset_sql($sql, array(), $page*$perpage, $perpage);

$ages = array("<20", "20-24", "25-29", "30-34", "35-39", "40-44", "45-49", "50-54", "55-59", ">=60");
$gender = array();
$gender["Male"] = array(0, 0, 0, 0, 0, 0, 0, 0, 0, 0);
$gender["Female"] = array(0, 0, 0, 0, 0, 0, 0, 0, 0, 0);
 foreach ($rs as $rc) {
	 $age_grp = (int) $rc->age_group;
	 if ($age_grp < 4) {
		$gender[$rc->gender][0] = $gender[$rc->gender][0] + $rc->total;
	 } elseif ($age_grp <= 11) {
		$gender[$rc->gender][$age_grp - 3] = $gender[$rc->gender][$age_grp - 3] + $rc->total;
	 } elseif ($age_grp > 11) {
		$gender[$rc->gender][9] = $gender[$rc->gender][9] + $rc->total;
	 }

}
$rs->close();


 ?>
 <script type="text/javascript">
 
  // Set a callback to run when the Google Visualization API is loaded.
  google.setOnLoadCallback(drawAge);
  
  // Callback that creates and populates a data table, 
  // instantiates the pie chart, passes in the data and
  // draws it.
  function drawAge() {

  // Create our data table.
	var data = new google.visualization.DataTable();
	
  var raw_data = [['Male', <?php echo implode(", ", $gender["Male"]); ?>],
                  ['Female', <?php echo implode(", ", $gender["Female"]); ?>]];
  
  var ages = ['<?php echo implode ("', '", $ages); ?>'];
                  
  data.addColumn('string', 'Age');
  for (var i = 0; i  < raw_data.length; ++i) {
    data.addColumn('number', raw_data[i][0]);    
  }
  
  data.addRows(ages.length);

  for (var j = 0; j < ages.length; ++j) {    
    data.setValue(j, 0, ages[j].toString());    
  }
  for (var i = 0; i  < raw_data.length; ++i) {
    for (var j = 1; j  < raw_data[i].length; ++j) {
      data.setValue(j-1, i+1, raw_data[i][j]);    
    }
  }

	// Create and draw the visualization.
	  var bchart = new google.visualization.ColumnChart(document.getElementById('cchart_age_div'));
	  bchart.draw(data,
           {title:'',
            width:650, height:175,
            vAxis: {title: ""},
            hAxis: {title: "Age"}}
      );

  }
</script>
<h3>Reports: Age</h3>
   <!--Div that will hold the pie chart-->
    <div id="cchart_age_div"></div>
	<br/>
<?php
}
if($gen==1){
$sql = "SELECT d.data as gender, count(*) as total FROM user_info_data d, user_info_field f where d.fieldid = f.id AND f.shortname = 'Gender' GROUP BY gender";
if($extrasql){
	$sql = "SELECT 
				da.gender AS gender,
				COUNT(*) AS total
			FROM
				( select 
					`u`.`id` AS `id`,
					(select `d`.`data` AS `data` from `user_info_data` `d`, user_info_field f where ((`d`.`fieldid` = f.id) and (`d`.`userid` = `u`.`id`) and f.name='Gender')) AS `gender`,
					`u`.`lastlogin` AS `lastlogin` 
				from `user` `u` 
				where (`u`.`deleted` = 0 AND $extrasql)) da 
			where da.gender!=''
			GROUP BY gender 
		";
}


$rs = $DB->get_recordset_sql($sql, array(), $page*$perpage, $perpage);
$table = array();
 foreach ($rs as $rc) {
    $row = array();
    $row[] = s($rc->gender);
    $row[] = $rc->total;

    $table[] = array($row[0] => $row[1]);
}
$rs->close();

?>

<script type="text/javascript">
  // Set a callback to run when the Google Visualization API is loaded.
  google.setOnLoadCallback(drawGender);
  
  // Callback that creates and populates a data table, 
  // instantiates the pie chart, passes in the data and
  // draws it.
  function drawGender() {

  // Create our data table.
	var data = new google.visualization.DataTable();
	data.addColumn('string', 'Gender');
	data.addColumn('number', 'Total');

	<?php
		for ($i = 0; $i< sizeof($table); ++$i ) {
			foreach ($table[$i] as $id => $total) {
				echo "data.addRow(['" . $id . "', " . $total . "]);\n";
			}
		}
	?>

	// Create and draw the visualization.
	  var bchart = new google.visualization.BarChart(document.getElementById('bchart_gender_div'));
	  bchart.draw(data,
           {title:'',
            width:400, height:175,
            vAxis: {title: "Gender"},
            hAxis: {title: ""}}
      );

	// Instantiate and draw our chart, passing in some options.
	var pchart = new google.visualization.PieChart(document.getElementById('pchart_gender_div'));
	pchart.draw(data, 
		{width: 250, height: 175,
		 is3D: true,
		 title: ''});
  }
</script>
<h3>Reports: Gender</h3>
   <!--Div that will hold the pie chart-->
   <table><tr><td id="bchart_gender_div"></td><td id="pchart_gender_div"></td></tr></table>
   <br/>
<?php
}
if($load==1){
$sql = "SELECT f.name AS loantaken, count(*) AS total
	FROM user_info_data d, user_info_field f
	WHERE d.fieldid = f.id 
		AND d.data = '1' 
		AND (f.name = 'Personal loan' 
			OR f.name = 'Education Loan'
			OR f.name = 'Housing Loan'
			OR f.name = 'Credit Card'
			OR f.name = 'Car Loan'
			OR f.name = 'Others')
	GROUP BY loantaken
	ORDER BY loantaken";
if($extrasql){
	$sql = "select 
			`f`.`name` AS `loantaken`, 
			count(*) AS total 
		from `user_info_data` `d`, user_info_field f 
		where (
			(`d`.`fieldid` = f.id) 
			and (
				f.name = 'Personal loan' 
				OR f.name = 'Education Loan'
				OR f.name = 'Housing Loan'
				OR f.name = 'Credit Card'
				OR f.name = 'Car Loan'
				OR f.name = 'Others'
			) and d.data=1 
			and d.userid in (select u.id from `user` `u` where (`u`.`deleted` = 0 and $extrasql))
		) 
		GROUP BY loantaken
		ORDER BY loantaken";

}

$rs = $DB->get_recordset_sql($sql, array(), $page*$perpage, $perpage);
$table = array();
 foreach ($rs as $rc) {
    $row = array();
    $row[] = s($rc->loantaken);
    $row[] = $rc->total;

    $table[] = array($row[0] => $row[1]);
}
$rs->close();

?>

<script type="text/javascript">
  
  // Set a callback to run when the Google Visualization API is loaded.
  google.setOnLoadCallback(drawloan);
  
  // Callback that creates and populates a data table, 
  // instantiates the pie chart, passes in the data and
  // draws it.
  function drawloan() {

  // Create our data table.
	var data = new google.visualization.DataTable();
	data.addColumn('string', 'Loan Taken');
	data.addColumn('number', 'Total');

	<?php
		for ($i = 0; $i< sizeof($table); ++$i ) {
			foreach ($table[$i] as $id => $total) {
				echo "data.addRow(['" . $id . "', " . $total . "]);\n";
			}
		}
	?>
	// Create and draw the visualization.
	  var bchart = new google.visualization.BarChart(document.getElementById('bchart_loan_div'));
	  bchart.draw(data,
           {title:'',
            width:400, height:175,
            vAxis: {title: "Loan Taken"},
            hAxis: {title: ""}}
      );

	// Instantiate and draw our chart, passing in some options.
	var pchart = new google.visualization.PieChart(document.getElementById('pchart_loan_div'));
	pchart.draw(data, 
		{width: 250, height: 175,
		 is3D: true,
		 title: ''});
  }
</script>

<h3>Reports: Loan Taken</h3>
   <!--Div that will hold the pie chart-->
   <table><tr><td id="bchart_loan_div"></td><td id="pchart_loan_div"></td></tr></table>
   <br/>
<?php
}
if($marital){
$sql = "SELECT m.data AS marital, d.data AS gender, count(*) AS total
		FROM user_info_data d, user_info_field f,
			user_info_data m, user_info_field n
		WHERE (d.fieldid = f.id AND f.shortname = 'Gender')
			AND (m.fieldid = n.id AND n.shortname = 'Marital')
			AND (d.userid = m.userid)
		GROUP BY marital, gender
		ORDER BY marital";
if($extrasql){
	$sql = "SELECT 
				da.marital As marital,
				da.gender AS gender,
				COUNT(*) AS total
			FROM
				( select 
					(select `d`.`data` AS `data` from `user_info_data` `d`, user_info_field f where ((`d`.`fieldid` = f.id) and (`d`.`userid` = `u`.`id`) and f.name='Gender')) AS `gender`,
					(select `d`.`data` AS `data` from `user_info_data` `d`, user_info_field f where ((`d`.`fieldid` = f.id) and (`d`.`userid` = `u`.`id`) and f.name='Marital Status')) AS `marital`
				from `user` `u` 
				where (`u`.`deleted` = 0 AND $extrasql)) da 
			where da.gender!=''
			GROUP BY marital, gender
			ORDER BY marital ASC
		";
}


$rs = $DB->get_recordset_sql($sql, array(), $page*$perpage, $perpage);
$maritals = array("Single", "Married", "Divorced");
$gender = array();
$gender["Male"] = array(0, 0, 0);
$gender["Female"] = array(0, 0, 0);
 foreach ($rs as $rc) {
	switch($rc->marital) {
		case "Single":
			$gender[$rc->gender][0] = $gender[$rc->gender][0] + $rc->total;
			break;
		case "Married":
			$gender[$rc->gender][1] = $gender[$rc->gender][1] + $rc->total;
			break;
		case "Divorced":
			$gender[$rc->gender][2] = $gender[$rc->gender][2] + $rc->total;
			break;
	}
}
$rs->close();
?>

<script type="text/javascript">
  // Set a callback to run when the Google Visualization API is loaded.
  google.setOnLoadCallback(drawmarital);
  
  // Callback that creates and populates a data table, 
  // instantiates the pie chart, passes in the data and
  // draws it.
  function drawmarital() {

  // Create our data table.
	var data = new google.visualization.DataTable();
	
  var raw_data = [['Male', <?php echo implode(", ", $gender["Male"]); ?>],
                  ['Female', <?php echo implode(", ", $gender["Female"]); ?>]];
  
  var maritals = ['<?php echo implode ("', '", $maritals); ?>'];
                  
  data.addColumn('string', 'Age');
  for (var i = 0; i  < raw_data.length; ++i) {
    data.addColumn('number', raw_data[i][0]);    
  }
  
  data.addRows(maritals.length);

  for (var j = 0; j < maritals.length; ++j) {    
    data.setValue(j, 0, maritals[j].toString());    
  }
  for (var i = 0; i  < raw_data.length; ++i) {
    for (var j = 1; j  < raw_data[i].length; ++j) {
      data.setValue(j-1, i+1, raw_data[i][j]);    
    }
  }

	// Create and draw the visualization.
	  var bchart = new google.visualization.BarChart(document.getElementById('bchart_marital_div'));
	  bchart.draw(data,
           {title:'',
            width:650, height:175,
            vAxis: {title: "Marital Status"},
            hAxis: {title: ""}}
      );

  }
</script>
<h3>Reports: Marital Status</h3>
   <!--Div that will hold the pie chart-->
    <div id="bchart_marital_div"></div>
	<br/>
<?php 
}
if($income){
$sql = "SELECT m.data AS income, d.data AS gender, count(*) AS total
		FROM user_info_data d, user_info_field f,
			user_info_data m, user_info_field n
		WHERE (d.fieldid = f.id AND f.shortname = 'Gender')
			AND (m.fieldid = n.id AND n.shortname = 'Monthlyincome')
			AND (d.userid = m.userid)
		GROUP BY income, gender
		ORDER BY income";
		
if($extrasql){
	$sql = "SELECT 
				da.income As income,
				da.gender AS gender,
				COUNT(*) AS total
			FROM
				( select 
					(select `d`.`data` AS `data` from `user_info_data` `d`, user_info_field f where ((`d`.`fieldid` = f.id) and (`d`.`userid` = `u`.`id`) and f.name='Gender')) AS `gender`,
					(select `d`.`data` AS `data` from `user_info_data` `d`, user_info_field f where ((`d`.`fieldid` = f.id) and (`d`.`userid` = `u`.`id`) and f.shortname = 'Monthlyincome')) AS `income`
				from `user` `u` 
				where (`u`.`deleted` = 0 AND $extrasql)) da 
			where da.gender!=''
			GROUP BY income, gender
			ORDER BY income ASC
		";
}

$rs = $DB->get_recordset_sql($sql, array(), $page*$perpage, $perpage);
$incomes = array("< RM 1,500", "RM 1,501 - RM 2,500", "RM 2,501 - RM 3,500", "RM 3,501 - RM 5,000", "> RM 5,000");
$gender = array();
$gender["Male"] = array(0, 0, 0, 0, 0);
$gender["Female"] = array(0, 0, 0, 0, 0);
 foreach ($rs as $rc) {
	for ($i = 0; $i < sizeof($incomes); ++$i) {
		if ($rc->income == $incomes[$i]) {
			$gender[$rc->gender][$i] = $gender[$rc->gender][$i] + $rc->total;
		}
	}
}
$rs->close();
?>

<script type="text/javascript">
  // Set a callback to run when the Google Visualization API is loaded.
  google.setOnLoadCallback(drawIncome);
  
  // Callback that creates and populates a data table, 
  // instantiates the pie chart, passes in the data and
  // draws it.
  function drawIncome() {

  // Create our data table.
	var data = new google.visualization.DataTable();
	
  var raw_data = [['Male', <?php echo implode(", ", $gender["Male"]); ?>],
                  ['Female', <?php echo implode(", ", $gender["Female"]); ?>]];
  
  var incomes = ['<?php echo implode ("', '", $incomes); ?>'];
                  
  data.addColumn('string', 'Total');
  for (var i = 0; i  < raw_data.length; ++i) {
    data.addColumn('number', raw_data[i][0]);    
  }
  
  data.addRows(incomes.length);

  for (var j = 0; j < incomes.length; ++j) {    
    data.setValue(j, 0, incomes[j].toString());    
  }
  for (var i = 0; i  < raw_data.length; ++i) {
    for (var j = 1; j  < raw_data[i].length; ++j) {
      data.setValue(j-1, i+1, raw_data[i][j]);    
    }
  }

	// Create and draw the visualization.
	  var bchart = new google.visualization.BarChart(document.getElementById('bchart_income_div'));
	  bchart.draw(data,
           {title:'',
            width:650, height:175,
            vAxis: {textStyle: {fontSize: 10}},
            hAxis: {title: ""},
			isStacked: true}
      );

  }
</script>
<h3>Reports: Monthly Income</h3>
   <!--Div that will hold the bar chart-->
    <div id="bchart_income_div"></div>
	<br/>
<?php
}
if($qua){
$sql = "SELECT d.data as qualification, count(*) as total FROM user_info_data d, user_info_field f where d.fieldid = f.id AND f.shortname = 'Qualification' GROUP BY qualification";
if($extrasql){
	$sql = "SELECT 
				da.qualification AS qualification,
				COUNT(*) AS total
			FROM
				( select 
					`u`.`id` AS `id`,
					(select `d`.`data` AS `data` from `user_info_data` `d`, user_info_field f where ((`d`.`fieldid` = f.id) and (`d`.`userid` = `u`.`id`) and f.name='Qualification')) AS `qualification`,
					`u`.`lastlogin` AS `lastlogin` 
				from `user` `u` 
				where (`u`.`deleted` = 0 AND $extrasql)) da 
			where da.qualification!=''
			GROUP BY qualification 
		";
}

$rs = $DB->get_recordset_sql($sql, array(), $page*$perpage, $perpage);
$table = array();
 foreach ($rs as $rc) {
    $row = array();
    $row[] = s($rc->qualification);
    $row[] = $rc->total;

    $table[] = array($row[0] => $row[1]);
}
$rs->close();

?>

<script type="text/javascript">
  // Set a callback to run when the Google Visualization API is loaded.
  google.setOnLoadCallback(drawqua);
  
  // Callback that creates and populates a data table, 
  // instantiates the pie chart, passes in the data and
  // draws it.
  function drawqua() {

  // Create our data table.
	var data = new google.visualization.DataTable();
	data.addColumn('string', 'Qualification');
	data.addColumn('number', 'Total');

	<?php
		for ($i = 0; $i< sizeof($table); ++$i ) {
			foreach ($table[$i] as $qualification => $total) {
				echo "data.addRow(['" . $qualification . "', " . $total . "]);\n";
			}
		}
	?>

	// Create and draw the visualization.
	  var bchart = new google.visualization.BarChart(document.getElementById('bchart_qualification_div'));
	  bchart.draw(data,
           {title:'',
            width:400, height:175,
            vAxis: {title: "Qualification"},
            hAxis: {title: ""}}
      );

	// Instantiate and draw our chart, passing in some options.
	var pchart = new google.visualization.PieChart(document.getElementById('pchart_qualification_div'));
	pchart.draw(data, 
		{width: 250, height: 175,
		 is3D: true,
		 title: '',});
  }
</script>

<h3>Reports: Qualification</h3>
   <!--Div that will hold the pie chart-->
   <table><tr><td id="bchart_qualification_div"></td><td id="pchart_qualification_div"></td></tr></table>

<?php
}
echo $OUTPUT->footer();

