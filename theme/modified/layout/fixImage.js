function fixHe(refer,picheight){
	if(refer.clientWidth){
		wide=refer.clientWidth;
	}else{
		wide=refer.offsetWidth;
	}
	a=wide/1024*picheight;

	return a;
}

function fixImage(refer,id,url,picheight,percent){
	if(refer.clientWidth){
		wide=refer.clientWidth;
	}else{
		wide=refer.offsetWidth;
	}
	a=fixHe(refer,picheight);
	document.getElementById(id).innerHTML = '<img src='+url+' style="width:'+wide*percent+'px;height:'+a*percent+'px;" />';
}