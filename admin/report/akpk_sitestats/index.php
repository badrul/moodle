<?php
require(dirname(dirname(dirname(dirname(__FILE__)))).'/config.php');
require_once($CFG->libdir.'/adminlib.php');

 admin_externalpage_setup('sitestats');
 echo $OUTPUT->header();
 ?>
 <script>
 window.setTimeout('fixImage(document.getElementById("maincontent"),"sitestats","<?php echo new moodle_url('/admin/report/akpk_sitestats/sitestats.png');?>",350)',1000);
 </script>
<span id="sitestats"></span> 
<?php
echo $OUTPUT->footer();