 <?php 
	require_once('config.php');
	$PAGE->set_url('/hirepurchase.php');
	$PAGE->set_course($SITE);
	$PAGE->set_pagelayout('small');
	$PAGE->set_pagetype('site-index');
    $PAGE->set_title($SITE->fullname);
    $PAGE->set_heading($SITE->fullname);
 echo $OUTPUT->header(); 
 ?>
<script language="javascript">
	function amountformat(value){
		var sRegExp = new RegExp('(-?[0-9]+)([0-9]{3})');
		sValue=value.toFixed(2).toString();
		while(sRegExp.test(sValue)) {
			sValue = sValue.replace(sRegExp, '$1,$2');
		}
		return sValue;
		
	}
	
	function loanamount(){
		var Amount = new Number(document.getElementById('loan').value);
		var Down = new Number(document.getElementById('downpayment').value);
		var Total= Amount-Down;
		res=document.getElementById('loanamount');
		res.innerHTML=Total;
	}
	
	function cal()
	{
		if(document.getElementById('loan').value <= 0 ){
			document.getElementById('err').innerHTML='<?php echo get_string('purchasepriceerr');?>';
		}else if(new Number(document.getElementById('downpayment').value)>new Number(document.getElementById('loan').value)){
			document.getElementById('err').innerHTML='<?php echo get_string('downpaymenterr');?>';
		}else if(document.getElementById('rate').value <= 0){
			document.getElementById('err').innerHTML='<?php echo get_string('interestrateerr');?>';
		}else if(document.getElementById('term').value <= 0){
			document.getElementById('err').innerHTML='<?php echo get_string('termerr');?>';
		}else{
			document.getElementById('loan').innerHTML='';

			var Amount = new Number(document.getElementById('loan').value);
			var Down = new Number(document.getElementById('downpayment').value);
			var Total= Amount-Down;
			var Rate = new Number(document.getElementById('rate').value);
			var Term = new Number(document.getElementById('term').value); 
			var Mrate = ( Rate / 100 ) / 12 ;
			var Mterm = Term * 12 ;
			
			var Interest=Total*Rate/100*Term;
			var Totalpay=Interest+Total;
			var Pay = Totalpay/Mterm ;
			
			var text='<table class="smalltable"><tr><th COLSPAN="2"><?php echo get_string('hpresult');?></th></tr><tr><td><?php echo get_string('purchaseprice');?></td><td align=right>' + amountformat(Amount) + '</td></tr><tr><td><?php echo get_string('downpayment');?></td><td align=right>' + amountformat(Down) + '</td></tr><tr><td><?php echo get_string('loanamount');?></td><td align="right">' + amountformat(Total) + '</td></tr><tr><td><?php echo get_string('interestrate');?></td><td align=right>' + amountformat(Rate) + '</td></tr><tr><td><?php echo get_string('loanperioad');?></td><td align="right">' + Term + '</td></tr><tr><td><?php echo get_string('totalinterest');?></td><td align="right">' + amountformat(Interest) + '</td></tr><tr><td><?php echo get_string('totalpayment');?></td><td align="right">' + amountformat(Totalpay) + '</td></tr><tr><td><?php echo get_string('monthlypayment');?></td><td align="right">' + amountformat(Pay) + '</td></tr></table><center><input onclick="location.reload(true)" class="button" type="button" value="<?php echo get_string('startover');?>"></center>' ;
		
			cal=document.getElementById('cal');
			cal.innerHTML=text;

		}

	}
</script>
	<div style="text-align:right;margin-bottom:2px;margin-right:-20px;"><a href="<?php echo new moodle_url(get_string('hpdllink'));?>" target="_blank"><button><?php echo get_string('download');?></button></a></div>
<div id="cal">
	<table class="smalltable" >
		<tr>
			<th COLSPAN="2"><?php echo get_string('hpcalculator');?></th>
		</tr>

		<tr>
			<td><?php echo get_string('purchaseprice');?></td>
			<td ><input id="loan" onchange="loanamount();"></td>
		</tr>
		
		<tr>
			<td><?php echo get_string('downpayment');?></td>
			<td ><input id="downpayment" onchange="loanamount();" onblur="loanamount();"></td>
		</tr>
		
		<tr>
			<td><?php echo get_string('loanamount');?></td>
			<td ><span id="loanamount"></td>
		</tr>

		<tr>
			<td><?php echo get_string('interestrate');?></td>
			<td ><input id="rate"></td>
		</tr>
		
		<tr>
			<td><?php echo get_string('loanperioad');?></td>
			<td ><input id="term"></td>
		</tr>

		<tr>
			<td COLSPAN="2"><hr></td>
		</tr>
		<tr>
			<td COLSPAN="2" style="color:red;"><span id="err"></td>
		</tr>

		<tr>
			<td COLSPAN="2">
				<center><input onclick="cal();" class="button" type="button" value="<?php echo get_string('calculate');?>"></center>
			</td>
		</tr>
	</table>	
</div>


<?php
echo $OUTPUT->footer();