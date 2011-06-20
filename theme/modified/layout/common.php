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

if ($hascustommenu) {
    $bodyclasses[] = 'has-custom-menu';
}

$ua='';
if(strpos($_SERVER['HTTP_USER_AGENT'],'MSIE 7')  || strpos($_SERVER['HTTP_USER_AGENT'],'MSIE 8')|| strpos($_SERVER['HTTP_USER_AGENT'],'MSIE 9')){
	$ua='iex6';
	$bodyclasses[] = 'iex6';
}

echo $OUTPUT->doctype() ?>
<html <?php echo $OUTPUT->htmlattributes() ?>>
<head>
    <title><?php echo $PAGE->title ?></title>
    <link rel="shortcut icon" href="<?php echo $OUTPUT->pix_url('favicon', 'theme')?>" />
    <?php echo $OUTPUT->standard_head_html() ?>
	<script type='text/javascript' src="<?php echo new moodle_url('/theme/modified/layout/fixImage.js');?>">
	</script>
		
</head>

<body id="<?php echo $PAGE->bodyid ?>" class="<?php echo $PAGE->bodyclasses.' '.join(' ', $bodyclasses) ?>">
<?php echo $OUTPUT->standard_top_of_body_html() ?>

<div id="page">

    <?php if ($hasheading || $hasnavbar) { ?>
           <div id="wrapper" class="clearfix">

<!-- START OF HEADER -->

            <div id="page-header" class="inside">
                <div id="page-header-wrapper" class="wrapper clearfix">

                    <?php if ($hasheading) { ?>
                        <h1 class="headermain"><?php //echo $PAGE->heading ?></h1>
						<?php 
						if($ua=='iex6'){
							echo '<img class="headbg" src="'.new moodle_url(get_string('headerbgurl')).'"/>';
							echo '<script type="text/javascript">
							window.setTimeout(\'document.getElementById("headbg").style.height=fixHe(document.getElementById("page-header"),160)+"px";document.getElementById("headbg").style.width=document.getElementById("page-header").offsetWidth+"px";\',1000);
							
							</script>';
						}
						?>
                        <div class="<?php echo get_string('headermenuclass');?>" id="headermenu"><?php
                            echo $OUTPUT->login_info();
                            echo $OUTPUT->lang_menu();
                            echo $PAGE->headingmenu ?>
                        </div>
                    <?php } ?>
					<div id="custommenu">
                    <?php if ($hascustommenu) { ?>
                    <?php echo $custommenu; ?>
                    <?php } ?>
					<?php topmenu();?>
					</div>
                </div>
            </div>

            <?php if ($hasnavbar) { ?>
                <div class="navbar">
                    <div class="wrapper clearfix">
                        <div class="breadcrumb"><?php echo $OUTPUT->navbar(); ?></div>
                        <div class="navbutton"> <?php echo $PAGE->button; ?></div>
                    </div>
                </div>
            <?php } ?>

<!-- END OF HEADER -->

    <?php } ?>


<!-- START OF CONTENT -->

        <div id="page-content-wrapper" class="wrapper clearfix">
            <div id="page-content">
                <div id="region-main-box">
                    <div id="region-post-box" style="width:110%;">
            
                        <div id="region-main-wrap">
                            <div id="region-main">
                                <div class="region-content">
                                    <?php echo core_renderer::MAIN_CONTENT_TOKEN ?>
                                </div>
                            </div>
                        </div>
                
                    </div>
                </div>
            </div>
        </div>

<!-- END OF CONTENT -->

    <?php if ($hasheading || $hasnavbar) { ?>
    <div class="myclear"></div>
        </div>
    <?php } ?>

<!-- START OF FOOTER -->

        <?php if ($hasfooter) { ?>
            <div id="page-footer" class="wrapper">
                <p class="helplink"><?php //echo page_doc_link(get_string('moodledocslink')) ?></p>
		   <script language="Javascript" src="<?php echo new moodle_url('/counter/?page=olp');?>"></script><br>
				<?php echo get_string('copyright');?>
		   <a href="<?php echo new moodle_url('/privacy.php');?>"><?php echo get_string('privacy');?> </a>
                <?php
                    //echo $OUTPUT->login_info();
                    //echo $OUTPUT->home_link();
                    echo $OUTPUT->standard_footer_html();
                ?>
            </div>
        <?php } ?>

</div>
<script type='text/javascript'>
	window.setTimeout('document.getElementById("headermenu").style.height=fixHe(document.getElementById("page-header-wrapper"),160)+"px";',1000);
</script>
<?php echo $OUTPUT->standard_end_of_body_html() ?>
</body>
</html>