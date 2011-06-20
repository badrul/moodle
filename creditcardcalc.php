 <?php 
	require_once('config.php');
	$PAGE->set_url('/creditcardcalc.php');
	$PAGE->set_course($SITE);
	$PAGE->set_pagelayout('small');
	$PAGE->set_pagetype('site-index');
    $PAGE->set_title($SITE->fullname);
    $PAGE->set_heading($SITE->fullname);
 echo $OUTPUT->header(); 
 ?>
<script language="javascript">
	
	function pay1(){
		if(document.getElementById('paymethod').value==1){
			document.getElementById('payamtlabel').innerHTML='<?php echo get_string("fixamount");?>';
			document.getElementById('payamt').innerHTML='<input id="p" value="1" type="hidden"><input id="pay" value="0">';
		}else{
			document.getElementById('payamtlabel').innerHTML='';
			document.getElementById('payamt').innerHTML='<input id="p" value="0" type="hidden">';
		}
		
	}
	

	function cal()
	{
	
		var pmt = 0;
		
		if(document.getElementById('principal').value <= 0 ){
			document.getElementById('err').innerHTML="<?php echo get_string('balanceerr');?>";
		}else{
			document.getElementById('err').innerHTML='';
			var i = new Number(document.getElementById('interest').value)/12/100;
 
			var j = 0.05;
 
			var prin0 = eval(document.getElementById('principal').value);
			
			var prin=prin0;
			
			var fix=eval(document.getElementById('p').value);
			if(fix==1){
				var pay =new Number(document.getElementById('pay').value);
				var pmt =pay;
				if(pmt<eval(prin * j) || pmt<50){
					document.getElementById('err').innerHTML='<?php echo get_string("payerror")?>';
					pmt=0;
				}
			}else if(eval(prin * j) < 50) {
				pmt = 50; 
			} else { 
				pmt = eval(j * prin); 
			}
			if(pmt>0){
				var prinPort = 0;
				var intPort = 0;
				var count = 0;
				
				while(prin > 0) {
					if(fix==0){
						if(eval(prin * j) < 50) {
							pmt = 50; 
						} else { 
							pmt = eval(j * prin).toFixed(2); 
						}
					}
					intPort = eval(i * prin).toFixed(2);
					prinPort = eval(pmt - intPort).toFixed(2);
					prin = eval(prin - prinPort).toFixed(2);
					count = count + 1;
					if(count > 600) { break; } else { continue;}
				}
				cal=document.getElementById('cal');
				h=cal.offsetHeight;
				var a="<?php echo get_string('creditcardmsg');?>";
				a=a.replace('$principal',prin0);
				a=a.replace('$count',count);
				if(fix==1){
					a=a.replace('$msg','<?php echo get_string("fixpay")?>');a=a.replace('$pay',pay);
				}else{
					a=a.replace('$msg','<?php echo get_string("minpayment")?>');
				}
				
				cal.innerHTML='<table class="smalltable" ><tr><th><?php echo get_string('creditcalcresult');?></th></tr><tr><td>'+a+'</td></tr><tr><td><center><input onclick="location.reload(true)" class="button" type="button" value="<?php echo get_string('startover');?>"></center></td></tr></table>';
				cal.style.height=h+"px";
			}
 
			
					
		}

	}
</script>
<div id="cal">
	<div style="text-align:right;margin-bottom:2px;margin-right:-20px;"><a href="<?php echo new moodle_url(get_string('creditcalcdllink'));?>" target="_blank"><button><?php echo get_string('download');?></button></a></div>
	<table class="smalltable" >
		<tr>
			<th COLSPAN="2"><?php echo get_string('creditcalc');?></th>
		</tr>

		<tr>
			<td><?php echo get_string('balance');?></td>
			<td ><input id="principal"></td>
		</tr>
		
		<tr>
			<td><?php echo get_string('interestrate');?></td>
			<td ><input id="interest" value="18"></td>
		</tr>
		
		<tr>
			<td COLSPAN="2">
				<select id="paymethod" onchange="pay1();">
					<option value="0"><?php echo get_string('paymin');?></option>
					<option value="1"><?php echo get_string('payfix');?></option>
				</select>
			</td>
		</tr>
		
		<tr>
			<td><span id="payamtlabel" /></td>
			<td><span id="payamt" /><input id="p" value="0" type="hidden"></td>
		</tr>

		<tr>
			<td COLSPAN="2"><hr></td>
		</tr>
		<tr>
			<td COLSPAN="2" style="color:red;"><span id="err"/> </td>
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