 <?php 
	require_once('config.php');
	$PAGE->set_url('/privacy.php');
	$PAGE->set_course($SITE);
	$PAGE->set_pagelayout('common');
	$PAGE->set_pagetype('site-index');
    $PAGE->set_title($SITE->fullname);
    $PAGE->set_heading($SITE->fullname);
 echo $OUTPUT->header(); 
 ?>
<h2><?php echo get_string('disclaimercontent');?></h2>
<div align="justify">
	<?php echo get_string('privacycontent');?>
</div>
<?php
echo $OUTPUT->footer();
