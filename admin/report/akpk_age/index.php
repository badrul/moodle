<?php
require(dirname(dirname(dirname(dirname(__FILE__)))).'/config.php');
require_once($CFG->libdir.'/adminlib.php');
require_once($CFG->dirroot.'/admin/report/filter.php');

 admin_externalpage_setup('age');
 echo $OUTPUT->header();

$ufiltering = new filter(array('Age'=>0,'Gender'=>0,'Qualification'=>0,'Marital Status'=>0,'Loan Taken'=>0,'Monthly Income'=>0,'lastlogin'=>0));
// page parameters
$page    = optional_param('page', 0, PARAM_INT);
$perpage = optional_param('perpage', 30, PARAM_INT);    // how many per page
$sort    = optional_param('sort', 'timemodified', PARAM_ALPHA);
$dir     = optional_param('dir', 'DESC', PARAM_ALPHA);
list($extrasql, $params) = $ufiltering->get_sql_filter();

/*
+-----------+------+--------+-------+
| age_group | age  | gender | total |
+-----------+------+--------+-------+
|         0 |    0 | Male   |     1 |
|         5 |   26 | Female |     1 |
|         5 |   29 | Male   |     2 |
|         6 |   30 | Male   |     2 |
+-----------+------+--------+-------+
*/
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
				FROM_UNIXTIME(da.DOB)) / (365 * 5)) AS age_group,
				DATE_FORMAT( FROM_DAYS( DATEDIFF( NOW( ) , FROM_UNIXTIME( da.DOB ) ) ) , '%Y' ) +0 AS age, 
				da.Gender AS gender,
				COUNT(*) AS total
					from 
						(select 
						`u`.`id` AS `id`,(select `c`.`data` from `user_info_data` `c` where ((`c`.`fieldid` = 11) and (`c`.`userid` = `u`.`id`))) AS `Address`,
						`u`.`city` AS `City`,
						(select `c`.`data` from `user_info_data` `c` where ((`c`.`fieldid` = 14) and (`c`.`userid` = `u`.`id`))) AS `State`,
						(select `c`.`data` from `user_info_data` `c` where ((`c`.`fieldid` = 4) and (`c`.`userid` = `u`.`id`))) AS `Gender`,
						(select `c`.`data` AS `data` from `user_info_data` `c` where ((`c`.`fieldid` = 5) and (`c`.`userid` = `u`.`id`))) AS `MaritalStatus`,
						(select `c`.`data` AS `data` from `user_info_data` `c` where ((`c`.`fieldid` = 6) and (`c`.`userid` = `u`.`id`))) AS `Qualification`,
						(select `c`.`data` AS `data` from `user_info_data` `c` where ((`c`.`fieldid` = 7) and (`c`.`userid` = `u`.`id`))) AS `Noofdependants`,
						(select `c`.`data` AS `data` from `user_info_data` `c` where ((`c`.`fieldid` = 8) and (`c`.`userid` = `u`.`id`))) AS `DOB`,
						(select `c`.`data` AS `data` from `user_info_data` `c` where ((`c`.`fieldid` = 2) and (`c`.`userid` = `u`.`id`))) AS `Monthlyincome`,
						(select `c`.`data` AS `data` from `user_info_data` `c` where ((`c`.`fieldid` = 16) and (`c`.`userid` = `u`.`id`))) AS `PersonalLoan`,
						(select `c`.`data` AS `data` from `user_info_data` `c` where ((`c`.`fieldid` = 19) and (`c`.`userid` = `u`.`id`))) AS `EducationLoan`,
						(select `c`.`data` AS `data` from `user_info_data` `c` where ((`c`.`fieldid` = 20) and (`c`.`userid` = `u`.`id`))) AS `HousingLoan`,
						(select `c`.`data` AS `data` from `user_info_data` `c` where ((`c`.`fieldid` = 21) and (`c`.`userid` = `u`.`id`))) AS `CreditCard`,
						(select `c`.`data` AS `data` from `user_info_data` `c` where ((`c`.`fieldid` = 22) and (`c`.`userid` = `u`.`id`))) AS `CarLoan`,
						(select `c`.`data` AS `data` from `user_info_data` `c` where ((`c`.`fieldid` = 23) and (`c`.`userid` = `u`.`id`))) AS `Others`,`u`.`lastlogin` AS `lastlogin` from `user` `u` where (`u`.`deleted` = 0) 
						) da
					where $extrasql and da.Gender!=''  
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
            width:650, height:350,
            vAxis: {title: ""},
            hAxis: {title: "Age"}}
      );

  }
</script>

<?php
 $ufiltering->display_add();
 $ufiltering->display_active();
?>

<h3>Reports: Age</h3>
   <!--Div that will hold the pie chart-->
    <div id="cchart_age_div"></div>

<?php
echo $OUTPUT->footer();
