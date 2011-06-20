<?php
require(dirname(dirname(dirname(dirname(__FILE__)))).'/config.php');
require_once($CFG->libdir.'/adminlib.php');
require_once($CFG->dirroot.'/admin/report/filter.php');

 admin_externalpage_setup('income');
 echo $OUTPUT->header();

 
// page parameters
$page    = optional_param('page', 0, PARAM_INT);
$perpage = optional_param('perpage', 30, PARAM_INT);    // how many per page
$sort    = optional_param('sort', 'timemodified', PARAM_ALPHA);
$dir     = optional_param('dir', 'DESC', PARAM_ALPHA);
$ufiltering = new filter(array('Age'=>0,'Gender'=>0,'Qualification'=>0,'Marital Status'=>0,'Loan Taken'=>0,'Monthly Income'=>0,'lastlogin'=>0));
list($extrasql, $params) = $ufiltering->get_sql_filter();
/*
+---------------------+--------+-------+
| income              | gender | total |
+---------------------+--------+-------+
| < RM 1,500          | Male   |     3 |
| RM 1,501 - RM 2,500 | Female |     1 |
| RM 3,501 - RM 5,000 | Male   |     2 |
+---------------------+--------+-------+
*/

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
				da.Monthlyincome As income,
				da.Gender AS gender,
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
			where da.Gender!='' AND $extrasql
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

//echo "<pre>";
//print_r($gender);
//echo "</pre>";

?>

<!--Load the AJAX API-->
<script type="text/javascript" src="https://www.google.com/jsapi"></script>
<script type="text/javascript">

  // Load the Visualization API and the piechart package.
  google.load('visualization', '1', {'packages':['corechart']});
  
  // Set a callback to run when the Google Visualization API is loaded.
  google.setOnLoadCallback(drawChart);
  
  // Callback that creates and populates a data table, 
  // instantiates the pie chart, passes in the data and
  // draws it.
  function drawChart() {

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
            width:650, height:350,
            vAxis: {textStyle: {fontSize: 10}},
            hAxis: {title: ""},
			isStacked: true}
      );

  }
</script>
<?php
	$ufiltering->display_add();
	$ufiltering->display_active();
?>
<h3>Reports: Monthly Income</h3>
   <!--Div that will hold the bar chart-->
    <div id="bchart_income_div"></div>

<?php
echo $OUTPUT->footer();