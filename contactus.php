 <?php 
	require_once('config.php');
	$PAGE->set_url('/contactus.php');
	$PAGE->set_course($SITE);
	$PAGE->set_pagelayout('common');
	$PAGE->set_pagetype('site-index');
    $PAGE->set_title($SITE->fullname);
    $PAGE->set_heading($SITE->fullname);
 echo $OUTPUT->header(); 
 ?>
<div class="contactus">

			<div style="margin-left:33%">
				<h2><?php echo get_string('hq');?></h2>
				<h3>Kuala Lumpur</h3> 
				<p>
					Level 8, Maju Junction Mall<br />
					1001 Jalan Sultan Ismail<br />
					50250 Kuala Lumpur<br />
					Tel: 603 2616 7766<br />
					Fax: 603 2616 7601</font>
				</p>
				<br />
				<p>Contact Centre: 1 800 88 2575</p>
	
	</div>

	<h2><?php echo get_string('branch');?></h2>

	<table border="0" style="width: 100%;" class="branch">
		<tr>
			<td>
				<h3>Penang</h3> 
				<p>
					Bangunan Bank Negara Malaysia<br />
					27 Corner Light/Pitt Street<br />
					10200 Penang<br />
					Tel : 604 261 2246<br />
					Fax : 604 261 2243
				</p>
			</td>
			<td>
				<h3>Kuching</h3>
				<p>
					Bangunan Bank Negara Malaysia<br />
					Jalan Satok<br />
					93720 Kuching<br />
					Tel :  6082 414 910<br />
					Fax : 6082 414 960
				</p>
			</td>
			<td>
				<h3>Kuantan</h3>
				<p>
					G-02 Mahkota Square<br />
					Jalan Mahkota<br />
					25000 Kuantan<br />
					Tel :  609 513 3190<br />
					Fax : 609 513 3255<br />
				</p>
			</td>
		</tr>
		<tr>
			<td>
				<h3>Johor Bahru</h3>
				<p>
					Bangunan Bank Negara Malaysia<br />
					Jalan Bukit Timbalan<br />
					80720 Johor Bahru<br />
					Tel :  607 221 0533<br />
					Fax : 607 221 0535
				</p>
			</td>
			<td>
				<h3>Kota Kinabalu</h3>
				<p>
					Bangunan Bank Negara Malaysia<br />
					Jalan Lapan Belas<br />
					88000 Kota Kinabalu<br />
					Tel :  6088 538 355<br />
					Fax : 6088 538 377
				</p>
			</td>
			<td>
				<h3>Ipoh</h3>
				<p>
					Unit B-2-1 Greentown Square<br />
					Jalan Dato' Seri Admad Said<br />
					30450 Ipoh<br />
					Perak<br />
					Tel :  605 242 8319<br />
					Fax : 605 242 8452
				</p>
			</td>
		</tr>
		<tr>
			<td>
				<h3>Kuala Terengganu</h3>
				<p>
					Bangunan Bank Negara Malaysia<br />
					Jalan Sultan Mohamad<br />
					21100 Kuala Terengganu<br />
					Tel :  609 631 2797<br />
					Fax : 609 631 2801
				</p>
			</td>
			<td>
				<h3>Malacca</h3>
				<p>
					Ground &amp; Mezzanine Floor<br />
					No 179,<br />
					Bangunan Munshi Abdullah<br />
					Jalan Munshi Abdullah<br />
					75100 Melaka<br />
					Tel :  606 292 1238<br />
					Fax : 606 292 1251
				</p>
			</td>
			<td>
				<h3>Alor Setar</h3>
				<p>
					No 11, Ground Floor<br/>
					Jalan Persiaran Sultan Abdul Hamid,<br />
					Kompleks Sultan Abdul Hamid,<br />
					05050 Alor Setar<br/>
					Kedah Darul Aman<br />
					Tel : 604 771 5773<br />
					Fax : 604 771 5737
				</p>
			</td>
		</tr>
		<tr>
			<td>
				<h3>Kota Bharu</h3>
				<p>
					PT5, Ground Floor<br/>
					Kota Indah,<br/> 
					Jln Sultan Yahya Petra,<br/>
					15200 Kota Bharu<br />
					Tel : 609 747 6968<br />
					Fax : 609 746 1085
				</p>
			</td>
			<td></td>
			<td></td>
		</tr>
	</table>
</div>
<?php
echo $OUTPUT->footer();