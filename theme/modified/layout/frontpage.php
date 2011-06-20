<?php
require(dirname(__FILE__).'/topmenu.php');
$hasheading = ($PAGE->heading);
$hasnavbar = (empty($PAGE->layout_options['nonavbar']) && $PAGE->has_navbar());
$hasfooter = (empty($PAGE->layout_options['nofooter']));
$hassidepost = $PAGE->blocks->region_has_content('side-post', $OUTPUT);
$showsidepost = ($hassidepost && !$PAGE->blocks->region_completely_docked('side-post', $OUTPUT));

$custommenu = $OUTPUT->custom_menu();
$hascustommenu = (empty($PAGE->layout_options['nocustommenu']) && !empty($custommenu));

$bodyclasses = array();
if ($showsidepost) {
    $bodyclasses[] = 'side-post-only';
} else if (!$showsidepost) {
    $bodyclasses[] = 'content-only';
}

$ua='';
if(strpos($_SERVER['HTTP_USER_AGENT'],'MSIE 7')  || strpos($_SERVER['HTTP_USER_AGENT'],'MSIE 8')|| strpos($_SERVER['HTTP_USER_AGENT'],'MSIE 9')){
	$ua='iex6';
	$bodyclasses[] = 'iex6';
}
if( strpos($_SERVER['HTTP_USER_AGENT'],'MSIE 8')){
	$bodyclasses[] = 'ie8';
}

if ($hascustommenu) {
    $bodyclasses[] = 'has-custom-menu';
}

echo $OUTPUT->doctype() ?>
<html <?php echo $OUTPUT->htmlattributes() ?>>
<head>
    <title><?php echo $PAGE->title ?></title>
    <link rel="shortcut icon" href="<?php echo $OUTPUT->pix_url('favicon', 'theme')?>" />
    <meta name="description" content="<?php echo strip_tags(format_text($SITE->summary, FORMAT_HTML)) ?>" />
    <?php echo $OUTPUT->standard_head_html() ?>
	<script type='text/javascript' src="<?php echo new moodle_url('/theme/modified/layout/fixImage.js');?>">
		
	</script>
	
	<?php if(empty($USER->username)){ ?>
		<script type='text/javascript'>
		window.setTimeout('fixImage(document.getElementById("region-main"),"power","<?php echo new moodle_url(get_string('introurl')); ?>",472.6,1)', 1000);
		</script>
	<?php } ?>
</head>

<body id="<?php echo $PAGE->bodyid ?>" class="<?php if(!empty($USER->username)){echo $PAGE->bodyclasses.' '.join(' ', $bodyclasses);}else{echo $PAGE->bodyclasses.' '.'content-only'.' '.$ua;} ?>">
<?php echo $OUTPUT->standard_top_of_body_html() ?>

<div id="page">

    <div id="wrapper" class="clearfix">

<!-- START OF HEADER -->

        <div id="page-header">
			<span id="head"></span>
            <div id="page-header-wrapper" class="wrapper clearfix">
					
                <h1 class="headermain"><?php //echo $PAGE->heading ?></h1>
				<?php 
						if($ua=='iex6'){
							echo '<img class="headbg" src="'.new moodle_url(get_string('headerbgurl')).'"/>';
							echo '<script type="text/javascript">
							window.setTimeout(\'document.getElementById("headbg").style.height=fixHe(document.getElementById("page-header"),160)+"px";document.getElementById("headbg").style.width=document.getElementById("page-header").offsetWidth+"px";\',1000);
							
							</script>';
						}
				?>
                <div class="<?php echo get_string('headermenuclass');?>" id="headermenu">
					<br/>
                    <?php
                        echo $OUTPUT->login_info();
                        echo $OUTPUT->lang_menu();
                        echo $PAGE->headingmenu;
                    ?>
                </div>
				<div id="custommenu">
                <?php if ($hascustommenu) { ?>
                
				<?php echo $custommenu; ?>
				<?php } ?>
				<?php topmenu();?>
                
				</div>
			</div>
		</div>   
<!-- END OF HEADER -->

<!-- START OF CONTENT -->

        <div id="page-content-wrapper" class="wrapper clearfix">
            <div id="page-content">
                <div id="region-main-box">
                    <div id="region-post-box">
            
                        <div id="region-main-wrap">
                            <div id="region-main">
                                <div class="region-content" <?php if (empty($USER->username)){echo "style='display:none'";}?>>
									<?php if(!empty($USER->username)){ ?>
									<div id="mybg">
									<center>
									<object  classid="clsid:D27CDB6E-AE6D-11cf-96B8-444553540000" codebase="http://fpdownload.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=8,0,0,0" id="f">
									<!--<object>-->
									<param name="wmode" value="transparent">
									<param name="movie" value="<?php echo new moodle_url('/theme/modified/pix/AKPK_Introduction.swf');?>" />
									<embed src="<?php echo new moodle_url('/theme/modified/pix/AKPK_Introduction.swf');?>" type="application/x-shockwave-flash" style="margin:auto;min-width:653px;min-height:490px;" wmode="transparent"/>
									</object>
									</center>
									</div>
									<script type='text/javascript'>
	window.setTimeout('document.getElementById("f").style.height=fixHe(document.getElementById("mybg"),707.9)+"px";document.getElementById("f").style.width=document.getElementById("mybg").offsetWidth+"px";',1000);
	
</script>
									<br/>
									<?php }?>
									<?php echo core_renderer::MAIN_CONTENT_TOKEN; ?>
                                </div>
								
								<?php 
									if(empty($USER->username)){
									?>
									<span id='power'></span>
									<?php
									}
								?>
																		
								

                            </div>
                        </div>
                
                        <?php if (!empty($USER->username) && $hassidepost) { ?>
                        <div id="region-post" class="block-region">
                            <div class="region-content">
                                <?php echo $OUTPUT->blocks_for_region('side-post') ?>
                            </div>
                        </div>
                        <?php } ?>

                    </div>
                </div>
            </div>
            <div class="myclear" ></div>

        </div>
       

<!-- END OF CONTENT -->
    <div class="myclear" style="height:0;"></div>
       </div> <!-- END #wrapper -->

<!-- START OF FOOTER -->
   <div id="footer" class="myclear">
           <p class="helplink"></p>
		   <script language="Javascript" src="<?php echo new moodle_url('/counter/?page=olp');?>"></script><br>
		   <?php echo get_string('copyright');?>
		   <a href="<?php echo new moodle_url('/privacy.php');?>"><?php echo get_string('privacy');?> </a>
        <?php
            echo $OUTPUT->standard_footer_html();
           ?>
     </div>

<!-- END OF FOOTER -->

</div> <!-- END #page -->
<script type='text/javascript'>
	window.setTimeout('document.getElementById("headermenu").style.height=fixHe(document.getElementById("page-header-wrapper"),160)+"px";',1000);
	
</script>
<?php echo $OUTPUT->standard_end_of_body_html() ?>
</body>
</html>
