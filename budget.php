 <?php 
	require_once('config.php');
	$PAGE->set_url('/budget.php');
	$PAGE->set_course($SITE);
	$PAGE->set_pagelayout('small');
	$PAGE->set_pagetype('site-index');
    $PAGE->set_title($SITE->fullname);
    $PAGE->set_heading($SITE->fullname);
 echo $OUTPUT->header(); 
 ?>
<script language="javascript">
	
	function ini(field){
		var rownumber=initialcount(field);
		
		var i=1;
		while (i<=new Number(document.getElementById(field).value)){
			inputrow(rownumber,field,i);
			rownumber++;
			i++;
		}
		
		var row=document.getElementById('budget').insertRow(rownumber);
		var na=row.insertCell(0);
		var b=row.insertCell(1);
		var a=row.insertCell(2);
		
		if(field=='fe'){
			na.innerHTML='<h3><?php echo get_string('totalfe');?></h3>';
		}else if(field=='ve'){
			na.innerHTML='<h3><?php echo get_string('totalve');?></h3>';
		}else if(field=='de'){
			na.innerHTML='<h3><?php echo get_string('totalde');?></h3>';
		}
		b.innerHTML='<span id="total'+field+'b">0.00</span>';
		a.innerHTML='<span id="total'+field+'a">0.00</span>';
		b.setAttribute("class", 'result');
		a.setAttribute("class", 'result');
		a.setAttribute("align", 'right');
		b.setAttribute("align", 'right');
	}
	
	function inputrow(rownumber,name,ival){
		var row=document.getElementById('budget').insertRow(rownumber);
		var na=row.insertCell(0);
		var b=row.insertCell(1);
		var a=row.insertCell(2);
		
		na.innerHTML='<input id="name'+name+ival+'" />';
		na.setAttribute("class", 'fieldname');
		b.innerHTML='<input id="input'+name+'b'+ival+'" onchange="sum(\''+name+'b\',document.getElementById(\''+name+'\').value)" />';
		a.innerHTML='<input id="input'+name+'a'+ival+'" onchange="sum(\''+name+'a\',document.getElementById(\''+name+'\').value)" />';
	}
	
	function sum(field,num){

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
		recal();
		
	}
	
	function sum2(field){
		sum('income'+field,2);
		var i=1;
		var total=new Number(document.getElementById('totalincome'+field).innerHTML);
		while (i<=2){
			val=new Number(document.getElementById('inputsa'+field+i).value);
			if(val!=0){
				document.getElementById('inputsa'+field+i).value=val.toFixed(2);
				total-=val;
			}
			i++;
		}
		document.getElementById('totalsa'+field).innerHTML=total.toFixed(2);
		
	}
	
	function amountformat(value){
		var sRegExp = new RegExp('(-?[0-9]+)([0-9]{3})');
		sValue=value.toFixed(2).toString();
		while(sRegExp.test(sValue)) {
			sValue = sValue.replace(sRegExp, '$1,$2');
		}
		return sValue;
		
	}
	
	function initialcount(field){
		if(field=='fe'){
			return 10;
		}else if(field=='ve'){
			return 10+new Number(document.getElementById('fe').value)+2;
		}else if(field=='de'){
			return 10+new Number(document.getElementById('fe').value)+new Number(document.getElementById('ve').value)+4;
		}
	}
	
	function addfield(name){
		var ival=new Number(document.getElementById(name).value)+1;
		document.getElementById(name).value=ival;
		
		inputrow(ival+initialcount(name)-1,name,ival);
		recal();
	}
	
	function cal()
	{	
		var row=document.getElementById('budget').insertRow(-1);
		row.setAttribute("id", 'lastresult');
		var a=row.insertCell(0);
		var b=row.insertCell(1);
		var c=row.insertCell(2);
		
		a.innerHTML='<h3><?php echo get_string('excessordeficit');?></h3>';
		
		b.setAttribute("class", 'result');
		b.innerHTML=subanw('b');
		c.setAttribute("align", 'right');
		b.setAttribute("align", 'right');
		c.setAttribute("class", 'result');
		c.innerHTML=subanw('a');
		
		document.getElementById('button').innerHTML='<center><input onclick="location.reload(true)" class="button" type="button" value="<?php echo get_string('startover');?>"></center>';

	}
	
	function recal(){
		if(document.getElementById('lastresult')){
			document.getElementById('budget').deleteRow(-1);
		}
		document.getElementById('button').innerHTML='<center><input onclick="cal();" class="button" type="button" value="<?php echo get_string('calculate');?>"></center>';
	}
	
	function arr1(){
		var arr=new Array();
		arr[0]='fe';
		arr[1]='ve';
		arr[2]='de';
		return arr;
	}
	
	function subanw(name){
		var arr=arr1();
		
		answ=new Number(document.getElementById('totalsa'+name).innerHTML);
		for (i=0;i<=2;i++){
			answ-=new Number(document.getElementById('total'+arr[i]+name).innerHTML);
		}
		return answ.toFixed('2');
		
	}

</script>
<div id="cal2">
	<div style="text-align:right;margin-bottom:2px;margin-right:-20px;"><a href="<?php echo new moodle_url(get_string('budgetdllink'));?>" target="_blank"><button><?php echo get_string('download');?></button></a></div>
	<input id="fe" type="hidden" value="2"/>
	<input id="ve" type="hidden" value="2"/>
	<input id="de" type="hidden" value="2"/>
	<table id="budget" class="nettable" >
		<tr>
			<th COLSPAN="3"><?php echo get_string('budgetcalc');?></th>
		</tr>

		<tr>
			<td>
				<center><h3><?php echo get_string('item');?></h3></center>
			</td>
			<td >
				<center><h3><?php echo get_string('budget');?></h3></center>
			</td>
			<td>
				<center><h3><?php echo get_string('actualcashflow');?></h3></center>
			</td>
		</tr>
		
		<tr>
			<td>
				<h3><?php echo get_string('salary');?></h3>
			</td>
			<td>
				<input id="inputincomeb1" onchange="sum2('b')" />
			</td>
			<td>
				<input id="inputincomea1" onchange="sum2('a')" />
			</td>
		</tr>
		
		<tr>
			<td>
				<h3><?php echo get_string('othersources');?></h3>
			</td>
			<td >
				<input id="inputincomeb2" onchange="sum2('b')" />
			</td>
			<td >
				<input id="inputincomea2" onchange="sum2('a')" />
			</td>
		</tr>
		
		<tr>
			<td>
				<h3><?php echo get_string('totalmonthlyincome');?></h3>
			</td>
			<td class="result">
				<span id="totalincomeb">0.00</span>
			</td>
			<td class="result">
				<span id="totalincomea">0.00</span>
			</td>
		</tr>
		
		<tr>
			<td COLSPAN="3">
				<br/>
				<h3><?php echo get_string('less');?></h3>
			</td>
		</tr>
		
		<tr>
			<td>
				<h3><?php echo get_string('savings');?></h3>
			</td>
			<td >
				<input id="inputsab1" onchange="sum2('b')" />
			</td>
			<td >
				<input id="inputsaa1" onchange="sum2('a')" />
			</td>
		</tr>
		
		<tr>
			<td>
				<h3><?php echo get_string('emergencyfunds');?></h3>
			</td>
			<td >
				<input id="inputsab2" onchange="sum2('b')" />
			</td>
			<td >
				<input id="inputsaa2" onchange="sum2('a')" />
			</td>
		</tr>
		
		<tr>
			<td>
				<h3><?php echo get_string('netofsaving');?></h3>
			</td>
			<td class="result">
				<span id="totalsab">0.00</span>
			</td>
			<td class="result">
				<span id="totalsaa">0.00</span>
			</td>
		</tr>
		
		<tr>
			<td COLSPAN="2">
				<br/>
				<h3><?php echo get_string('lessfixexpense');?></h3>
				
			</td>
			<td style="text-align:right">
				<br/>
				<button onclick="addfield('fe');"><?php echo get_String('adddots');?></button>
			</td>
		</tr>
		
		<tr>
			<td COLSPAN="2">
				<br/>
				<h3><?php echo get_string('lessvarexpense');?></h3>
				
			</td>
			<td style="text-align:right">
				<br/>
				<button onclick="addfield('ve');"><?php echo get_String('adddots');?></button>
			</td>
		</tr>
		
		<tr>
			<td COLSPAN="2">
				<br/>
				<h3><?php echo get_string('lessdisexpense');?></h3>
				
			</td>
			<td style="text-align:right">
				<br/>
				<button class="button" onclick="addfield('de');"><?php echo get_String('adddots');?></button>
			</td>
		</tr>
		
		
	</table>
	<div id="button">
	<center>
		<input onclick="cal();" class="button" type="button" value="<?php echo get_string('calculate');?>">
	</center>
	</div>
	
</div>
<script language="javascript">
	window.setTimeout("ini('fe');ini('ve');ini('de');",1000);
</script>

<?php
echo $OUTPUT->footer();