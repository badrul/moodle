function fixH() {
		var contentheight =  document.getElementsByClassName('region-content');
		if(contentheight.length==2){
			lh=contentheight[0].offsetHeight;
			rh=contentheight[1].offsetHeight;
		
			var nh = Math.max(lh, rh);
			document.getElementById('region-main').style.height=nh+"px";
			document.getElementById('region-post').style.height=nh+"px";
		}
	}
	
function fixReportH(){
	var a=document.getElementById('report-region-wrap');
	var b=document.getElementById('page-content');
	var lh=a.offsetHeight;
	var rh=b.offsetHeight;
	var nh = Math.max(lh, rh);
	a.style.height=nh+"px";
	b.style.height=nh+"px";
}

/*
	window.setTimeout('fixH()', 50);
	window.setTimeout('fixH()', 10000);
	window.onload=function(){
		fixH();
        var anchors =  document.getElementsByClassName('block_action');
        for(var i = 0; i < anchors.length; i++) {
            var anchor = anchors[i];
            anchor.onclick = function() {
                fixH();
            }
        }

		var unlocks =  document.getElementsByClassName('controls');
        for(var i = 0; i < unlocks.length; i++) {
            unlocks[i].onclick = function() {
                fixH();
            }
        }
	}
*/