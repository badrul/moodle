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

echo $OUTPUT->doctype() ?>
<html <?php echo $OUTPUT->htmlattributes() ?>>
<head>
    <title><?php echo $PAGE->title ?></title>
    <link rel="shortcut icon" href="<?php echo $OUTPUT->pix_url('favicon', 'theme')?>" />
    <?php echo $OUTPUT->standard_head_html() ?>
		
</head>

<body id="<?php echo $PAGE->bodyid ?>" class="<?php echo $PAGE->bodyclasses.' '.join(' ', $bodyclasses) ?>">
<?php echo $OUTPUT->standard_top_of_body_html() ?>

<div id="pagesmall">

    <?php if ($hasheading || $hasnavbar) { ?>
           <div id="wrapper" class="clearfix">

<!-- START OF HEADER -->
                    <?php if ($hasheading) { ?>
                        <div height="0"><?php
                            echo $PAGE->headingmenu ?>
                        </div>
                    <?php } ?>
<!-- END OF HEADER -->

    <?php } ?>


<!-- START OF CONTENT -->

        <div id="page-content-wrapper" class="wrapper clearfix">
            <div id="page-content">
                <div id="region-main-box" style="padding-left:0px;width:100%;right:0;">
                    <div id="region-post-box" style="width:100%;margin-left:0">
            
                        <div id="region-main-wrap" style="width:100%;">
                            <div id="region-main" style="width:100%;margin-left:0;left:0">
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
	
                <?php
                   
                    echo $OUTPUT->standard_footer_html();
                ?>
            </div>
        <?php } ?>

</div>
<?php echo $OUTPUT->standard_end_of_body_html() ?>
</body>
</html>