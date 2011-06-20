<?php
require(dirname(dirname(dirname(dirname(__FILE__)))).'/config.php');
require_once($CFG->libdir.'/adminlib.php');
require_once($CFG->dirroot.'/admin/report/filter.php');

 admin_externalpage_setup('qualification');
 echo $OUTPUT->header();
 ?>

<?php

// page parameters
$page    = optional_param('page', 0, PARAM_INT);
$perpage = optional_param('perpage', 30, PARAM_INT);    // how many per page
$sort    = optional_param('sort', 'timemodified', PARAM_ALPHA);
$dir     = optional_param('dir', 'DESC', PARAM_ALPHA);
$ufiltering = new filter(array('Age'=>0,'Gender'=>0,'Qualification'=>0,'Marital Status'=>0,'Loan Taken'=>0,'Monthly Income'=>0,'lastlogin'=>0));
list($extrasql, $params) = $ufiltering->get_sql_filter();

$sql = "SELECT d.data as qualification, count(*) as total FROM user_info_data d, user_info_field f where d.fieldid = f.id AND f.shortname = 'Qualification' GROUP BY qualification";
if($extrasql){
	$sql = "SELECT 
				da.Qualification AS qualification,
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
			where da.Qualification!='' AND $extrasql
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

//print_r($table);

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
	data.addColumn('string', 'Qualification');
	data.addColumn('number', 'Total');

	<?php
		for ($i = 0; $i< sizeof($table); ++$i ) {
			foreach ($table[$i] as $qualification => $total) {
				echo "data.addRow(['" . $qualification . "', " . $total . "]);\n";
			}
		}
	?>
	//data.addRow(['Male', 11]);
	//data.addRow(['Female', 45]);

	// Create and draw the visualization.
	  var bchart = new google.visualization.BarChart(document.getElementById('bchart_qualification_div'));
	  bchart.draw(data,
           {title:'',
            width:650, height:350,
            vAxis: {title: "Qualification"},
            hAxis: {title: ""}}
      );

	// Instantiate and draw our chart, passing in some options.
	var pchart = new google.visualization.PieChart(document.getElementById('pchart_qualification_div'));
	pchart.draw(data, 
		{width: 650, height: 350,
		 is3D: true,
		 title: ''});
  }
</script>
 <?php
	$ufiltering->display_add();
	$ufiltering->display_active();
?>
<h3>Reports: Qualification</h3>
   <!--Div that will hold the pie chart-->
    <div id="bchart_qualification_div"></div>
    <div id="pchart_qualification_div"></div>

 
 <script>
 //window.setTimeout('fixImage(document.getElementById("maincontent"),"qualification","<?php echo new moodle_url('/admin/report/akpk_qualification/qualification.png');?>",350)',1000);
 </script>

<span id="qualification"></span> 
<?php
echo $OUTPUT->footer();