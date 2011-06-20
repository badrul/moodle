 <?php 
	require_once('config.php');
	$PAGE->set_url('/networth.php');
	$PAGE->set_course($SITE);
	$PAGE->set_pagelayout('small');
	$PAGE->set_pagetype('site-index');
    $PAGE->set_title($SITE->fullname);
    $PAGE->set_heading($SITE->fullname);
 echo $OUTPUT->header(); 
 ?>
<script language="javascript">

	function ini(field,num){
		
		var i=1;
		while (i<=num){
			name=field+i;
			var input='<input id="input'+name+'" onchange="sum(\''+field+'\','+num+')" />';
			c=document.getElementById(name);
			c.innerHTML=input;
			i++;
		}
	}
	
	function sum(field,num){
		document.getElementById('asset').innerHTML='';
		document.getElementById('liability').innerHTML='';
		document.getElementById('bottom').innerHTML='';
		var i=1;
		var total=new Number('0');
		while (i<=num){
			name=field+i;
			val=new Number(document.getElementById('input'+name).value);
			if(val!=0){
				document.getElementById('input'+name).value=val.toFixed(2);
				total+=val;
			}
			i++;
		}
		document.getElementById('total'+field).innerHTML=total.toFixed(2);
		
	}
	
	function amountformat(value){
		var sRegExp = new RegExp('(-?[0-9]+)([0-9]{3})');
		sValue=value.toFixed(2).toString();
		while(sRegExp.test(sValue)) {
			sValue = sValue.replace(sRegExp, '$1,$2');
		}
		return sValue;
		
	}
	
	function cal()
	{	
		a=new Number(document.getElementById('totalca').innerHTML)+new Number(document.getElementById('totalla').innerHTML)+new Number(document.getElementById('totalia').innerHTML);
		
		document.getElementById('asset').innerHTML='<table style="width:100%;font-weight:bold;"><tr><td><?php echo get_string('totalasset');?></td><td style="text-align:right;">'+a.toFixed(2)+'</td></tr></table>';
		
		l=new Number(document.getElementById('totalstl').innerHTML)+new Number(document.getElementById('totalltl').innerHTML);
		
		document.getElementById('liability').innerHTML='<table style="width:100%;font-weight:bold;"><tr><td><?php echo get_string('totalliability');?></td><td style="text-align:right;">'+l.toFixed(2)+'</td></tr></table>';
		
		total=a-l;
		
		document.getElementById('bottom').innerHTML='<?php echo get_string('networth');?> : '+amountformat(total);

	}

</script>
<div id="cal2">
	<div style="text-align:right;margin-bottom:2px;margin-right:-20px;"><button><a href="<?php echo new moodle_url(get_string('networthdllink'));?>" target="_blank"><?php echo get_string('download');?></a></button></div>
	<table class="nettable">
		<tr>
			<th COLSPAN="2"><?php echo get_string('networthcalc');?></th>
		</tr>

		<tr>
			<td>
				<h3><?php echo get_string('asset');?></h3>
				<table class="smalltable">
					<tr>
						<td COLSPAN="2"><h4><?php echo get_string('cashasset');?></h4></th>
					</tr>
					<tr>
						<td><?php echo get_string('saving');?></td>
						<td ><span id="ca1"></span></td>
					</tr>
					<tr>
						<td><?php echo get_string('currenacc');?></td>
						<td ><span id="ca2"></span></td>
					</tr>
					<tr>
						<td><?php echo get_string('fd');?></td>
						<td ><span id="ca3"></span></td>
					</tr>
					<tr>
						<td><?php echo get_string('lifeinsurance');?></td>
						<td ><span id="ca4"></span></td>
					</tr>
					<tr>
						<td><?php echo get_string('others');?></td>
						<td ><span id="ca5"></span></td>
					</tr>
					<tr>
						<td><b><?php echo get_string('total');?></b></td>
						<td class="result"><span id="totalca"/>0.00</td>
					</tr>

					<tr>
						<td COLSPAN="2"><h4><?php echo get_string('investmentasset');?></h4></th>
					</tr>
					<tr>
						<td><?php echo get_string('stock');?></td>
						<td ><span id="ia1"></span></td>
					</tr>
					<tr>
						<td><?php echo get_string('bon');?></td>
						<td ><span id="ia2"></span></td>
					</tr>
					<tr>
						<td><?php echo get_string('unittrust');?></td>
						<td ><span id="ia3"></span></td>
					</tr>
					<tr>
						<td><?php echo get_string('others');?></td>
						<td ><span id="ia4"></span></td>
					</tr>
					<tr>
						<td><b><?php echo get_string('total');?></b></td>
						<td class="result"><span id="totalia"/>0.00</td>
					</tr>
	
					<tr>
						<td COLSPAN="2"><h4><?php echo get_string('longtermasset');?></h4></th>
					</tr>
					<tr>
						<td><?php echo get_string('epf');?></td>
						<td ><span id="la1"></span></td>
					</tr>
					<tr>
						<td><?php echo get_string('landed');?></td>
						<td ><span id="la2"></span></td>
					</tr>
					<tr>
						<td><?php echo get_string('cars');?></td>
						<td ><span id="la3"></span></td>
					</tr>
					<tr>
						<td><?php echo get_string('jewellery');?></td>
						<td ><span id="la4"></span></td>
					</tr>
					<tr>
						<td><?php echo get_string('others');?></td>
						<td ><span id="la5"></span></td>
					</tr>
					<tr>
						<td><b><?php echo get_string('total');?></b></td>
						<td class="result"><span id="totalla"/>0.00</td>
					</tr>
				</table>
				
			</td>
			<td style="vertical-align:top;">
				<h3><?php echo get_string('liabilities');?></h3>
				<table class="smalltable">
					<tr>
						<td COLSPAN="2"><h4><?php echo get_string('shorttermliabilities');?></h4></th>
					</tr>
					<tr>
						<td><?php echo get_string('creditcardbills');?></td>
						<td ><span id="stl1"></span></td>
					</tr>
					<tr>
						<td><?php echo get_string('personalod');?></td>
						<td ><span id="stl2"></span></td>
					</tr>
					<tr>
						<td><?php echo get_string('shorttermborrowings');?></td>
						<td ><span id="stl3"></span></td>
					</tr>
					<tr>
						<td><?php echo get_string('incometaxpayable');?></td>
						<td ><span id="stl4"></span></td>
					</tr>
					<tr>
						<td><?php echo get_string('others');?></td>
						<td ><span id="stl5"></span></td>
					</tr>
					<tr>
						<td><b><?php echo get_string('total');?></b></td>
						<td class="result"><span id="totalstl"/>0.00</td>
					</tr>
					
					<tr>
						<td COLSPAN="2"><h4><?php echo get_string('longtermliabilities');?></h4></th>
					</tr>
					<tr>
						<td><?php echo get_string('housingloans');?></td>
						<td ><span id="ltl1"></span></td>
					</tr>
					<tr>
						<td><?php echo get_string('investmentproperties');?></td>
						<td ><span id="ltl2"></span></td>
					</tr>
					<tr>
						<td><?php echo get_string('hpoutstanding');?></td>
						<td ><span id="ltl3"></span></td>
					</tr>
					<tr>
						<td><?php echo get_string('familypersonalloan');?></td>
						<td ><span id="ltl4"></span></td>
					</tr>
					<tr>
						<td><?php echo get_string('educationloan');?></td>
						<td ><span id="ltl5"></span></td>
					</tr>
					<tr>
						<td><?php echo get_string('others');?></td>
						<td ><span id="ltl6"></span></td>
					</tr>
					<tr>
						<td><b><?php echo get_string('total');?></b></td>
						<td class="result"><span id="totalltl"/>0.00</td>
					</tr>
				</table>
				
			</td>
		</tr>
		<tr>
			<td><span id="asset"></td>
			<td><span id="liability"></td>
		</tr>
	</table>	
	<center>
		<div id="bottom">
		</div>
		<input onclick="cal();" class="button" type="button" value="<?php echo get_string('calculate');?>"/>
	</center>
	
</div>
<script language="javascript">
	window.setTimeout("ini('ca',5);ini('ia',4);ini('la',5);ini('stl',5);ini('ltl',6);",1000);
</script>

<?php
echo $OUTPUT->footer();