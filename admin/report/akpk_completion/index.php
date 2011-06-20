<?php
require(dirname(dirname(dirname(dirname(__FILE__)))).'/config.php');
require_once($CFG->libdir.'/adminlib.php');

 admin_externalpage_setup('completion');
 echo $OUTPUT->header();
 ?>
 <script>
 window.setTimeout('fixImage(document.getElementById("maincontent"),"completion","<?php echo new moodle_url('/admin/report/akpk_completion/completion.png');?>",350)',1000);
 </script>
<span id="completion"></span> 
<?php
echo $OUTPUT->footer();