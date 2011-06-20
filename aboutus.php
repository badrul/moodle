 <?php 
	require_once('config.php');
	$PAGE->set_url('/aboutus.php');
	$PAGE->set_course($SITE);
	$PAGE->set_pagelayout('common');
	$PAGE->set_pagetype('site-index');
    $PAGE->set_title($SITE->fullname);
    $PAGE->set_heading($SITE->fullname);
 echo $OUTPUT->header(); 
 ?>
 <br />
  <?php echo get_string("aboutuscontent");?>	
</div>
<?php
echo $OUTPUT->footer();
