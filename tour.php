 <?php 
	require_once('config.php');
	$PAGE->set_url('/contactus.php');
	$PAGE->set_course($SITE);
	$PAGE->set_pagelayout('tutorial');
	$PAGE->set_pagetype('site-index');
    $PAGE->set_title($SITE->fullname);
    $PAGE->set_heading($SITE->fullname);
 echo $OUTPUT->header(); 
 ?>
<div  align="justify">
<p style="font-weight:bold;">
	<?php //echo get_string('tutorialcontent');?>
</p>

</div>
<?php
echo $OUTPUT->footer();