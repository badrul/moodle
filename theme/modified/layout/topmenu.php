<?php
	function topmenu(){
		echo '
			<script type="text/javascript">
				function PopupCenter(pageURL, title,w,h) {
					var left = (screen.width/2)-(w/2);
					var top = (screen.height/2)-(h/2);
					
					var targetWin = window.open (pageURL, title, "toolbar=no,titlebar=no, location=no, directories=no, status=no, menubar=no, scrollbars=yes, resizable=1,titlebar=no, width="+w+", height="+h+", top="+top+", left="+left);
				} 
			
			
				var delay = 2000; var modd = 0;
				var bar = new menuBar();
				var ag = navigator.userAgent.toLowerCase();
				
				bar.addMenuItem("'.new moodle_url('/').'","'.get_string("home").'");
				
				bar.addMenu("'.get_string("financialtool").'");
				bar.addItemPopup("'.new moodle_url('/houseloan.php').'","'.get_string("housingloan").'",400,380);
				bar.addItemPopup("'.new moodle_url('/hirepurchase.php').'","'.get_string("hpcalculator").'",400,380);
				bar.addItemPopup("'.new moodle_url('/networth.php').'","'.get_string("networthcalc").'",725,650);
				bar.addItemPopup("'.new moodle_url('/creditcardcalc.php').'","'.get_string("creditcalc").'",400,350);
				bar.addItemPopup("'.new moodle_url('/budget.php').'","'.get_string("budgetcalc").'",700,650);
				
				bar.addMenuPopup("'.new moodle_url('/tutorial.php').'","Tutorial",750,500);
				
				bar.addMenu("'.get_string("link").'");
				bar.addItem("http://www.bnm.gov.my","Bank Negara Malaysia");
				bar.addItem("http://www.akpk.org.my","AKPK");
				bar.addItem("http://www.bankinginfo.com.my/","Banking Info");
				bar.addItem("http://www.facebook.com/pages/Kuala-Lumpur-Malaysia/AGENSI-KAUNSELING-DAN-PENGURUSAN-KREDIT-AKPK/92509128293","Facebook");
				bar.addItem("http://twitter.com/AKPK1","Twitter");
				bar.addItem("http://www.speaksens.com.my/","Speaksens");			
				bar.addMenuItem("'.new moodle_url('/aboutus.php').'","'.get_string("aboutus").'");
				
				bar.addMenuItem("'.new moodle_url('/contactus.php').'","'.get_string("contactus").'");
				
				bar.addMenu("'.get_string("others").'");
				bar.addItemPopup("'.new moodle_url('http://www.akpk.org.my/Faq/tabid/78/Default.aspx').'","FAQ",1024,768);
				bar.addItemPopup("'.new moodle_url('http://bankinginfo.com.my/04_help_and_advice/0406_glossary/glossary.php?intPrefLangID=1&').'","Glossary",1024,768);
				bar.addItem("","eBook");
				

				// do not change anything below this line

				function menuBar() {
					this.jj = -1;
					this.kk = 0;
					this.mO = new Array();
					this.addMenu = addMenu;
					this.addMenuPopup = addMenuPopup;
					this.addMenuItem = addMenuItem;
					this.addItem = addItem;
					this.addItemPopup = addItemPopup;
					this.writeBar = writeBar;
					this.writeDrop = writeDrop;
				}
				
				function addMenu(main) {
					this.mO[++this.jj] = new Object();
					this.mO[this.jj].main = main;
					this.kk = 0;
					this.mO[this.jj].link = new Array();
					this.mO[this.jj].name = new Array();
				}
				
				function addMenuItem(link,name){
					this.mO[++this.jj] = new Object();
					this.mO[this.jj].main = name;
					this.mO[this.jj].url=link;
					this.mO[this.jj].link = new Array();
					this.mO[this.jj].name = new Array();
				}
				
				function addMenuPopup(link,name,w,h){
					this.mO[++this.jj] = new Object();
					this.mO[this.jj].main = name;
					this.mO[this.jj].url=link;
					this.mO[this.jj].oc=1;
					this.mO[this.jj].w=w;
					this.mO[this.jj].h=h;
					this.mO[this.jj].link = new Array();
					this.mO[this.jj].name = new Array();
				}
				
				function addItem(link,name) {
					this.mO[this.jj].link[this.kk] = link;
					this.mO[this.jj].name[this.kk++] = name;
				}
				
				function addItemPopup(link,name,w,h) {
					this.mO[this.jj].link[this.kk] = link;
					this.mO[this.jj][this.kk]=new Object();
					this.mO[this.jj][this.kk].w=w;
					this.mO[this.jj][this.kk].h=h;
					this.mO[this.jj].name[this.kk++] = name;
				}
				
				function writeBar() {
					for (var i=1;i <= this.mO.length; i++){
						if(this.mO[i-1].oc==1){
							text="PopupCenter(\'"+this.mO[i-1].url+"\',\'"+this.mO[i-1].main+"\', \'"+this.mO[i-1].w+"\', \'"+this.mO[i-1].h+"\')";
							document.write("<span id=\"navMenu"+i+"\" class=\"mH\" onclick=\""+text+";\">"+this.mO[i-1].main+"</span>");
						} else if(this.mO[i-1].url!=null){
							document.write("<a id=\"navMenu"+i+"\" class=\"mH\" href=\'"+this.mO[i-1].url+"\'>"+this.mO[i-1].main+"</a>");
						}else{
							document.write("<span id=\"navMenu"+i+"\" class=\"mH\" >"+this.mO[i-1].main+"</span>");
						}
					}
				}
				
				function writeDrop() {
					for (var i=1;i <= this.mO.length; i++){
						if(this.mO[i-1].link.length>0){
							document.write("<div id=\"dropMenu"+i+"\"  class=\"mD\">\r\n");
							for (var h=0;h < this.mO[i-1].link.length; h++){
								if(this.mO[i-1][h]!=null){
									if(ag.indexOf(\'msie\')>-1){
										document.write("<a class=\"mL\" href=\""+this.mO[i-1].link[h]+"\"  target=\"_blank\">"+this.mO[i-1].name[h]+"</a>\r\n");
									}else{
										text="PopupCenter(	\'"+this.mO[i-1].link[h]+"\', \'"+this.mO[i-1].name[h]+"\', \'"+this.mO[i-1][h].w+"\', \'"+this.mO[i-1][h].h+"\')";
										document.write("<a class=\"mL\" onclick=\""+text+";\">"+this.mO[i-1].name[h]+"</a>\r\n");
									}
							
								}else{
									document.write("<a class=\"mL\" href=\'"+this.mO[i-1].link[h]+"\'>"+this.mO[i-1].name[h]+"</a>\r\n");
								}
							}
							document.write("</div>\r\n");
						}
					}
				}
				
				window.onscroll=sMenu;
				window.onload=iMenu;
				var onm = null;
				var ponm = null;
				var podm = null;
				var ndm = bar.mO.length;

				function sMenu() { 
					for (i=1; i<=ndm; i++) {
						menuName = "dropMenu" + i;
						odm = document.getElementById(menuName);
						if (onm) {
							var yPos = onm.offsetParent.offsetTop + onm.offsetParent.offsetHeight;
							if(odm){
								odm.style.top = yPos + "px";
							}
						}
					}
				}
				
				function iMenu() {
					if (document.getElementById) {
						document.onclick = mHide;
						for (i=1; i<=ndm; i++) {
							navName = "navMenu" + i;
							onm = document.getElementById(navName); 
							menuName = "dropMenu" + i;
							if(bar.mO[i-1].link.length>0){
							odm = document.getElementById(menuName);
							odm.style.visibility = "hidden"; onm.onmouseover =  mHov; 
							onm.onmouseout = mOut;
							}
						} 
						onm = null;
					} 
					return;
				}
				
				function mHov(e) {
					if (modd) clearTimeout(modd);
					document.onclick = null;
					honm = document.getElementById(this.id);
					menuName = "drop" + this.id.substring(3,this.id.length);
					odm = document.getElementById(menuName); 
					if (podm == odm) {
						mHide();
						return;
					} 
					if (podm != null) mHide();
					onm = document.getElementById(this.id);
					if (odm) {
						xPos = onm.offsetParent.offsetLeft + onm.offsetLeft; 
						yPos = onm.offsetParent.offsetTop + onm.offsetParent.offsetHeight; 
						odm.style.left = xPos + "px";
						odm.style.top = yPos + "px";
						odm.style.visibility = "visible";
						odm.onmouseover = omov; 
						odm.onmouseout = omot;
						podm = odm; 
						ponm = onm;
					}
				}

				function omov() {if (modd) clearTimeout(modd);}
				
				function omot() {modd = setTimeout("mHide()",delay);}
				
				function mOut(e) {modd = setTimeout("mHide()",delay);document.onclick = mHide;oonm = document.getElementById(this.id); }
				
				function mHide() {document.onclick = null; if (podm) {podm.style.visibility = "hidden"; podm = null;  } onm = null;}
				
				
				var isG = (ag.indexOf("gecko") != -1);
				var isR=0;
				if (isG) {
					t = ag.split("rv:");
					isR = parseFloat(t[1]);
				}
				if ( isR) setInterval("sMenu()",50);

				if (document.getElementById) {
					document.writeln("<div id=\'mB\'>\r\n");
					bar.writeBar();
					document.write("\r\n</div>\r\n\r\n");
					bar.writeDrop();
				} else {
					document.writeln("<div id=\'mB\'><a class=\'mO\' href=\'"+mapLink+"\'>"+mapName+"</a></div>");
				}
			</script>
			<script type="text/javascript">

			  var _gaq = _gaq || [];
			  _gaq.push([\'_setAccount\', \'UA-23517623-1\']);
			  _gaq.push([\'_trackPageview\']);

			  (function() {
				var ga = document.createElement(\'script\'); ga.type = \'text/javascript\'; ga.async = true;
				ga.src = (\'https:\' == document.location.protocol ? \'https://ssl\' : \'http://www\') + \'.google-analytics.com/ga.js\';
				var s = document.getElementsByTagName(\'script\')[0]; s.parentNode.insertBefore(ga, s);
			  })();

			</script>
		';
	}
?>
