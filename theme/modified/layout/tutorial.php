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
<div id="tutorial">

<!-- START OF CONTENT -->
	<table border="0" cellpadding="0" cellspacing="0" class="maintable">
		<tr>
			<td class="topleft">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
			<td class="topmiddle"></td>
            <td class="topright">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
		</tr>
		<tr>
			<td class="left"></td>
			<td class="middle">
				<div id="content">
					<?php echo core_renderer::MAIN_CONTENT_TOKEN ?>
                </div>
            </td>
            <td class="right"></td>
		</tr>
		<tr>
			<td class="bottomleft"></td>
			<td class="bottommiddle"></td>
            <td class="bottomright"></td>
		</tr>
	</table>

<!-- END OF CONTENT -->


<!-- START OF FOOTER -->
	<?php if ($hasfooter) { ?>
		<div id="page-footer" class="wrapper">
			<?php echo $OUTPUT->standard_footer_html();?>
		</div>
	<?php } ?>

</div>
	
<?php echo $OUTPUT->standard_end_of_body_html() ?>
</body>
</html>