
/**
 * This class contains client-specific parameters and also provides access 
 * to the config info
 */
function FDCPClient()
{
	this.cpHost = "rockymountainnews.cleanprint.net";
	this.divid="2024";
	this.refid="";
	this.rt = "i";
	this.cpstatus = false;
	this.ptstatus = "y";
	this.printSpecId = 0
	this.fdDebug = false;
	this.cpc = null;
	this.blkwidth=0;
	this.xpathLib = "";
	this.shost = "";
	this.hosted = "fd";
	
	// templatetest var to pull templates from a directory
	this.templateTest = false;
	
	// 's' for standalone and 'c' for combined.  A standalone install turns off registration for
	// PrintTracker events
	this.insType = "c";

	this.escCom = function(st) {
		st = new st.constructor(st);
		st = st.replace(/:/g, "::");
		st = st.replace(/,/g, ":,");
		return st;
	}

	this.getSegment=function () { 
		var root_value = Root;
var sub1_value = Sub1;
var sub2_value = Sub2;
if(root_value ==""){
root_value="Other";
}
if(sub1_value ==""){
sub1_value="Other";
}
if(sub2_value ==""){
sub2_value="Other";
}
return this.escCom(root_value)+","+ this.escCom(sub1_value)+","+ this.escCom(sub2_value);
	}

	this.getPFF = function() { 
		return "0";
	}

	this.getVR = function() { 
		return {section:Root};
	}

	this.onPrint = function() { 
		
	}
	
	this.getBlockThreshold = function() {
		return 500;
	}

	this.getCfg=function(ckey, cdef) {
		if(this.cpc != null && typeof this.cpc[ckey] != 'undefined')
			return this.cpc[ckey];
		return cdef;
	}

	this.getTHost=function() {
		if(this.shost.length > 0 && document.location.protocol == "https:")
			return this.shost;
		else
			return this.cpHost;
	}
	this.getcpStat=function() { return this.getCfg('cpStatus', this.cpstatus); }
	this.getptStat=function() { return this.getCfg('ptStatus', this.ptstatus); }
	this.getDiv=function() { return this.getCfg('divisionId', this.divid); }
	this.getTmpl=function() { return this.getCfg('templateId', null); }
	this.getRfmt = function() { return this.getCfg('templateId', this.refid); }  
	this.getTPath = function() { return this.getCfg('tPath', null); }
	this.getLPath = function() { return this.getCfg('lPath', null); }
	this.getTO = function() { return this.getCfg('timeout', 10000); }
	this.getTemplateTest = function() { return this.getCfg('templateTest', this.templateTest); }
	this.getXpathLib = function() { return this.getCfg('xpathLib', this.xpathLib); }
	
	this.getFDDebug = function() { return this.getCfg('fdDebug', this.fdDebug); }
	
	this.getRType= function() {
		return this.rt;
	}
	
	this.getIframeUrls=function(){
		
	}
	
	this.cpServletPath=document.location.protocol + "//" + this.getTHost() + "/cp/psj";
}

var FDCPLoader = {

	count: 0,
	tint: 500,
	tmax: 10000,
	tagg: 0,
	incyc: false,
	loaded: false,
	divId: 0,
	printSpecId: 0,
	cpDef:{},
	FDCPClient:new FDCPClient(),

	getCfg: function(ckey, cdef) {
		if(this.cpc != null && typeof this.cpc[ckey] != 'undefined')
			return this.cpc[ckey];
		return cdef;
	},

	loadcp: function() {

		
		this.cpc = FDCPLoader.FDCPClient.cpc;

		if(this.cpc.cpStatus == 'n'){
			FDCPLoader.tagg = FDCPLoader.tmax + 1;
			return;
		}
		var jsloc = this.getCfg('codeBase', null);
		
		var e = document.createElement('script');
		e.src = this.validatePath(jsloc , "cp.js");
		e.type = 'text/javascript';
		document.getElementsByTagName("head")[0].appendChild(e);
		
		this.loadXPathLib();
	},
	
	loadXPathLib: function() {

		var xpathUrl = null;
		
		if (typeof this.FDCPClient.xpathLib == "undefined" || this.FDCPClient.xpathLib == null || this.FDCPClient.xpathLib == "")
		{
			xpathUrl = this.getCfg('xpathLib', null);
			
			if (xpathUrl == null){
				xpathUrl = this.getCfg('codeBase', null);
				xpathUrl = this.removeFileFromPath(xpathUrl, "cp.js");	
			}
		}
		else
			xpathUrl = this.FDCPClient.xpathLib;
		
		if (navigator.appName == "Microsoft Internet Explorer")
		{
			var e = document.createElement('script');
			e.src = this.validatePath(xpathUrl , "xpath.js");
			e.type = 'text/javascript';
			document.getElementsByTagName("head")[0].appendChild(e);
		}
	},
	
	removeFileFromPath: function(path, file){
	
		if (path.indexOf(file) > 1)
		{
			var lastSlashIndex = path.search(/[^/]*$/);
			return path.substr(0, lastSlashIndex);
		}
		else
		{
			return path;
		}
	},
	
	getPDScriptUrl: function(){
		
		var snodes = document.getElementsByName('cleanprintloader');
		var url = "";

		if(snodes.length > 0) {
			url = snodes[0].src;
		}
		else {

			snodes = document.getElementsByTagName('SCRIPT');

			for(var i = 0; i < snodes.length; i++) {
				if(snodes[i].name == 'cleanprintloader') {
					url = snodes[i].src;
					break;
				}
			}
		}
		
		return url;
	},
	
	
	loadPrintSpec: function(){
		if(!this.loaded) {
			this.loaded = true;
			var pdSrc = this.getPDScriptUrl();
			
			if (this.FDCPClient.hosted == "fd" && pdSrc.length > 0)
				this.divId = this.getDivisionId(pdSrc);
			else
				this.divId = this.FDCPClient.divid;
			
			this.printSpecId = this.getPrintSpecId(pdSrc);
			
			var url = this.FDCPClient.cpServletPath;
			url += "?useCache=false";
			url += "&divId=" + this.divId;

			var psid = null;

			if(typeof this.getCalculatedPrintSpecId != "undefined" && this.divId != 'tester')
			{
				try
				{
					psid = this.getCalculatedPrintSpecId();
				}
				catch(err)
				{
					return;
				}
			}

			url += "&printSpecId=" + ((typeof psid == 'undefined' || psid == null) ? this.printSpecId : psid);			
			
			var cpDefScript = document.createElement('script');
			cpDefScript.src = url;
			cpDefScript.type = 'text/javascript';
			document.getElementsByTagName("head")[0].appendChild(cpDefScript);
		}
	},

	getCalculatedPrintSpecId: function(){
		return 99;	
	},
		
	validatePath: function(path , jsFile){
		
		var phre = new RegExp("^http[s]?://[^/]*");

    	if(document.location.protocol == "https:" && path.match(phre) != null){
      		path = path.replace(phre, document.location.protocol + "//" + FDCPClient.getTHost());
      	}
		
		if (path.indexOf(jsFile) > 1)
			return path;
		else
		{
			if (path.charAt(path.length -1) == "/")
				return path + jsFile;
			else
				return path + "/" + jsFile;
		}	
	},
	
	getPrintSpecId: function(pdSrc)
	{
		var queryString = pdSrc.replace(/^[^\?]+\??/,'');
	
		var params = this.parseQuery( queryString );
	
		return params['ps'];
	
	},
	
	getDivisionId: function(pdSrc)
	{
		var tmpUrl = pdSrc.replace(/[^/]*$/,"");
		var lastSlashIndex = tmpUrl.search(/[^/]*$/);
		var divId = tmpUrl.substr(0, lastSlashIndex -1);
		lastSlashIndex = divId.search(/[^/]*$/);
		divId = divId.substr(lastSlashIndex, divId.length);
	
		return divId;
	},
	
	
	parseQuery: function( query ) {
	   var Params = new Object ();
	   if ( ! query ) return Params; // return empty object
	   var Pairs = query.split(/[;&]/);
	   for ( var i = 0; i < Pairs.length; i++ ) {
	      var KeyVal = Pairs[i].split('=');
	      if ( ! KeyVal || KeyVal.length != 2 ) continue;
	      var key = unescape( KeyVal[0] );
	      var val = unescape( KeyVal[1] );
	      val = val.replace(/\+/g, ' ');
	      Params[key] = val;
	   }
	   return Params;
	},
	
	cpJsLoaded:false,
	xpathJsLoaded:navigator.appName != "Microsoft Internet Explorer",
	registerModuleLoaded: function(moduleName){
		if(moduleName == "cp.js"){
			this.cpJsLoaded = true;
		}
		else if(moduleName == "xpath.js"){
			this.xpathJsLoaded = true;
		}
		if(this.xpathJsLoaded && this.cpJsLoaded){
			fdcp.replacePrintLinks();
		}
	}
	
}

function FDCPUrl(pfLink) {

	if(typeof fdcp == 'undefined') {

		if(FDCPLoader.incyc == false)
			FDCPLoader.incyc = true;

		if(FDCPLoader.tagg > FDCPLoader.tmax) {

			FDCPLoader.incyc = false;
			window.print();
			return false;
		}

		FDCPLoader.tagg += FDCPLoader.tint;

		setTimeout("FDCPUrl(" + pfLink + ")", FDCPLoader.tint);

		return false;
	}
	else
		FDCPLoader.incyc = false;

	fdcp.linkPrintHandler(pfLink);
	return false;
}

// a simple ajax object getter
function fdGetAjaxObj(){
	if(window.XMLHttpRequest){
		return new XMLHttpRequest(); //Not IE
	}else if(window.ActiveXObject){
		return new ActiveXObject("Microsoft.XMLHTTP"); //IE
	}else{
		return null;
	}
}








			function getCalculatedPrintSpecId()
			{
				return 99;
			}

		if (window.addEventListener) {
	window.addEventListener("load", function() { FDCPLoader.loadPrintSpec(); }, true);
}
else if (window.attachEvent) {
	window.attachEvent("onload", function() { FDCPLoader.loadPrintSpec(); });
}
//force after 10 seconds anyways in case something is hanging the page from firing the onload event (i.e. slow ad tag)
setTimeout("FDCPLoader.loadPrintSpec();", 8000);
