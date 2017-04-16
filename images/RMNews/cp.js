function FDCPBridge(_1){
this.count=0;
this.ifc=null;
this.oform;
this.tid=0;
this.fdcp=_1.fdcp;
this.pcallback=null;
this.fd_div_id="fd_page_main";
this.rmEl=new Array();
this.st=new Array();
this.nonescnames=["d","p","r","q","bn","bv"];
this.doesc=function(_2){
oloop:
for(var x in _2){
for(var n=0;n<this.nonescnames.length;n++){
if(x==this.nonescnames[n]){
continue oloop;
}
}
_2[x]=escape(_2[x]);
}
};
this.cleanPrint=function(_5,_6,_7){
if(this.fdcp.clt.getFDDebug()){
alert("DocDomain : ["+document.domain+"]");
}
this.pcallback=_7;
if(this.fdcp.browserDetect.browser=="Explorer"){
if(this.fdcp.clt.getFDDebug()){
alert("IE Printing");
}
window.print();
this.pcallback(true);
return;
}
if(typeof this.ifc!="undefined"&&this.ifc!=null){
document.body.removeChild(this.ifc);
document.body.removeChild(this.oform);
}
var _8=this.fdcp.getCpUrl();
var _9="fDContentFrame";
this.ifc=document.createElement("iframe");
this.ifc.setAttribute("src","about:blank");
this.ifc.setAttribute("id",_9);
this.ifc.setAttribute("NAME",_9);
this.ifc.setAttribute("loaded",false);
this.ifc.onload=function(){
loaded=true;
};
this.ifc.style.width="0px";
this.ifc.style.height="0px";
this.ifc.style.border="0px";
document.body.appendChild(this.ifc);
if(self.frames[_9].name!=_9){
self.frames[_9].name=_9;
}
this.oform=document.createElement("form");
document.body.appendChild(this.oform);
this.oform.action=_8;
this.oform.name="FDForm";
this.oform.method="post";
this.oform.target=_9;
for(var k in _5){
var pc=document.createElement("input");
pc.type="hidden";
pc.name=k;
pc.value=_5[k];
this.oform.appendChild(pc);
}
this.count=0;
if(this.fdcp.clt.getFDDebug()){
alert("CP Submit");
}
this.oform.submit();
this.tid=setInterval(function(){
this.fdcp.bridge.checkcontent();
},1000);
};
this.log=function(_c,_d,_e,_f){
var _10="rtype=log&";
_10+="LOG_LEVEL="+_c+"&";
_10+="LOG_MSG="+_d+"&";
if(typeof _f!="undefined"&&_f!=null){
for(var p in _f){
_10+="&"+p+"="+_f[p];
}
}
var _12=fdGetAjaxObj();
if(typeof _12=="undefined"||_12==null){
return;
}
_12.open("POST",_e,true);
_12.setRequestHeader("Content-type","application/x-www-form-urlencoded");
_12.send(_10);
};
this.checkcontent=function(){
try{
if(this.count<10){
if(this.ifc!=null&&typeof this.ifc.contentDocument!="undefined"){
if(typeof this.ifc.contentDocument.forms["EOC"]!="undefined"){
clearInterval(this.tid);
this.fdcp.enPt(false);
if(this.fdcp.clt.getFDDebug()){
alert("CP Content Pass - Print");
}
this.ifc.contentWindow.print();
this.fdcp.enPt(true);
this.pcallback(true);
return true;
}else{
this.count=this.count+1;
}
}else{
this.count=this.count+1;
}
}else{
clearInterval(this.tid);
this.pcallback(false);
return false;
}
}
catch(e){
if(this.fdcp.clt.getFDDebug()){
alert("CP Content Fail : ["+e.message+"]");
}
this.count=10;
clearInterval(this.tid);
this.pcallback(false);
}
};
this.removeelements=function(){
var _13=new RegExp("^(?:(http[s]?)://([^/:]+)(:[0-9]+)?)?(.*)");
var _14=this.fdcp.getCpUrl();
var _15=_13.exec(_14);
if(_15==null){
return false;
}
var _16=fdcp.clt.onPrint();
if(typeof _16!="undefined"&&_16!=null&&_16==false){
return false;
}
if(_15[2]!=""){
var d=_13.exec(window.location);
if(_15[1]+_15[2]+_15[3]!=d[1]+d[2]+d[3]){
return false;
}
}
var _18=this.fdcp.getCpPostData();
if(_18==null){
if(this.fdcp.clt.getFDDebug()){
alert("CP Content Fail");
}
return false;
}else{
if(this.fdcp.clt.getFDDebug()){
alert("CP Content Pass");
}
}
var _19="";
this.doesc(_18);
for(var x in _18){
_19+=x+"="+_18[x]+"&";
}
var _1b=fdGetAjaxObj();
if(typeof _1b=="undefined"||_1b==null){
return false;
}
_1b.open("POST",this.fdcp.getCpUrl(),false);
_1b.setRequestHeader("Content-type","application/x-www-form-urlencoded");
_1b.send(_19);
if(_1b.status==200){
var _1c=_1b.responseText;
if(_1c.indexOf("<FORM id=\"EOC\" />")!=-1){
var _1d=document.getElementById(this.fd_div_id);
if(_1d!=null){
if(this.togEl(true)){
_1d.style.display="block";
_1d.innerHTML=_1c;
}
}
}
}
};
this.revertback=function(){
var _1e=document.getElementById(this.fd_div_id);
if(_1e!=null){
_1e.innerHTML="";
_1e.style.display="none";
}
this.togEl(false);
};
this.togEl=function(bp){
var d=document;
var _21=d.body;
var _22=new Array();
if(bp){
var i=0;
for(i=0;i<_21.childNodes.length;i++){
if(typeof _21.childNodes[i].id!="undefined"&&_21.childNodes[i].id==this.fd_div_id){
continue;
}
this.rmEl.push(_21.childNodes[i]);
}
try{
for(i=0;i<this.rmEl.length;i++){
_21.removeChild(this.rmEl[i]);
_22.push(this.rmEl[i]);
}
}
catch(err){
if(_21.childNodes.length>0){
var e=_21.childNodes[0];
for(i=0;i<_22.length;i++){
_21.insertBefore(_22[i],e);
}
}else{
for(i=0;i<_22.length;i++){
_21.appendChild(_22[i]);
}
}
this.rmEl.length=0;
return false;
}
this.st=[];
if(d.styleSheets){
var ss;
for(var i=0;i<d.styleSheets.length;i++){
ss=d.styleSheets[i];
if(this.fdcp.clt.cpc.stylesheets!=undefined){
var _26=false;
for(var cps=0;cps<this.fdcp.clt.cpc.stylesheets.length;cps++){
if(ss.href==this.fdcp.clt.cpc.stylesheets[cps]){
_26=true;
break;
}
}
if(!_26){
if(ss.disabled==false){
ss.disabled=true;
this.st.push(ss);
}
}
}else{
if(ss.disabled==false){
ss.disabled=true;
this.st.push(ss);
}
}
}
}
}else{
for(var i=0;i<this.rmEl.length;i++){
_21.appendChild(this.rmEl[i]);
}
this.rmEl.length=0;
for(var i=0;i<this.st.length;i++){
this.st[i].disabled=false;
}
}
return true;
};
this.loadHandler=function(_28){
if(navigator.appName.indexOf("Microsoft")!=-1&&parseInt(navigator.appVersion)>=4&&navigator.userAgent.indexOf("Windows")!=-1){
if(document.body!=null){
var div=document.createElement("div");
div.setAttribute("id",this.fd_div_id);
div.style["display"]="none";
document.body.appendChild(div);
div.innerHTML="FD HIDDEN DIV";
}
if(_28.clt.cpc!=null&&_28.clt.getcpStat()=="y"){
window.attachEvent("onbeforeprint",function(){
_28.bridge.removeelements();
});
window.attachEvent("onafterprint",function(){
_28.bridge.revertback();
});
}
}
if(typeof Ajax=="undefined"){
var _2a=this.fdcp.clt.getCfg("ajaxlib");
if(typeof _2a=="undefined"||_2a==null){
return;
}
var e=document.createElement("script");
e.src=_2a;
e.type="text/javascript";
document.getElementsByTagName("head")[0].appendChild(e);
}
};
}
function FDCPUrl(_2c){
return fdcp.linkPrintHandler(_2c);
}
function FDCP(){
this.clt=FDCPLoader.FDCPClient;
this.bridge=new FDCPBridge({fdcp:this});
this.fdser=new FDSerializer(this.clt);
this.tstr=this.clt.getTPath();
this.logUrl=this.clt.getLPath();
this.pfLink=null;
this.linkClicked=false;
this.fdpt=null;
if(this.clt.getFDDebug()){
alert("TPath : ["+this.clt.getTPath()+"] Tmpl : ["+this.clt.getTmpl()+"] Div : ["+this.clt.getDiv()+"]");
}
if(this.clt.insType=="c"){
this.fdpt=new FormatDynamicsPT(this.clt);
}
this.cpEn=function(){
return fdcp.clt.cpc!=null&&fdcp.clt.getcpStat()=="y";
};
this.browserSupported=function(){
if(this.browserDetect.browser=="Opera"){
return false;
}else{
if((this.browserDetect.browser=="Safari")&&(this.browserDetect.OS=="Windows")){
return false;
}else{
return true;
}
}
};
this.getCpUrl=function(){
return this.tstr+"?"+(new Date()).getTime();
};
this.linkPrintHandler=function(_2d){
if(_2d!=undefined){
this.pfLink=_2d;
}
if(this.cpEn()==false||!this.browserSupported()){
this.CPFailover(false);
return true;
}
try{
if(fdcp.linkClicked!=true){
fdcp.linkClicked=true;
var _2e=this.getCpPostData();
this.clt.blkwidth=this.fdser.getWidestBlkWidth();
var _2f=this.clt.onPrint();
if(typeof _2f!="undefined"&&_2f!=null&&_2f==false){
if(this.clt.getFDDebug()){
alert("onPrint() returned "+_2f+", failing over");
}
this.CPFailover(false);
return false;
}
if(_2e!=null){
if(this.clt.getFDDebug()){
alert("CPPostData - Pass");
}
this.bridge.cleanPrint(_2e,this.clt.getTO(),function(_30){
fdcp.CPFailover(_30);
});
}else{
if(this.clt.getFDDebug()){
alert("CPPostData - Fail");
}
this.CPFailover(false);
}
}
}
catch(e){
if(this.clt.getFDDebug()){
alert("CPPostData - Fail");
}
fdcp.CPFailover(false);
}
return true;
};
this.getCpPostData=function(){
if(typeof this.clt.getDiv()=="undefined"||this.clt.getDiv()==null||this.clt.getDiv().length==0){
this.bridge.log("ERROR","No division defined",this.logUrl);
return null;
}
if(typeof this.clt.getSegment()=="undefined"||this.clt.getSegment()==null){
this.log("ERROR","No segment defined",this.logUrl);
return null;
}
var pc=null;
try{
pc=this.getPCXPath();
}
catch(e){
this.log("ERROR","Error parsing primary content.",this.logUrl);
return null;
}
if(pc!=null&&pc.length==0){
pc=null;
}
var _32=null;
try{
_32=this.getImages();
}
catch(e){
this.log("ERROR","Error parsing for image data.",this.logUrl);
return null;
}
var _33=this.clt.getTmpl();
if(typeof _33=="undefined"||_33==null||_33.length==0){
pc="";
this.tmpl="";
}
var _34={d:this.clt.getDiv(),a:navigator.appName+" "+navigator.userAgent,s:this.clt.getSegment(),u:window.location.href,p:this.clt.getPFF(),r:this.clt.getRfmt(),q:"1.0",bn:this.browserDetect.browser,bv:this.browserDetect.version,template:_33,ci:_32};
if(pc!=null){
_34.pc=pc;
}
var qp=this.clt.getVR();
if(typeof qp!="undefined"&&qp!=null){
for(var ki in qp){
_34[ki]=qp[ki];
}
}
if(this.clt.getTemplateTest()){
_34.tt=this.clt.getTemplateTest();
}
return _34;
};
this.getPCXPath=function(){
var _37=new Array();
for(var i=0;i<this.xpathDefs.length;i++){
var _39=this.xpathDefs[i];
if(_39.selection=="exclude"){
var _3a=this.getXPathNodes(_39);
if(_3a==null){
return null;
}
for(var j=0;j<_3a.length;j++){
_37.push(_3a[j]);
}
}
}
this.fdser.setExcludes(_37);
var pc=new Object();
var _3d="";
for(var i=0;i<this.xpathDefs.length;i++){
var _39=this.xpathDefs[i];
if(_39.selection=="include"){
if(typeof _39.target=="undefined"||_39.target==null||_39.target==""){
_39.target="default";
}
var _3e=this.getXPathNodes(_39);
if(_3e==null){
return null;
}
if((typeof pc[_39.target]=="undefined"||pc[_39.target]==null)&&_3e.length>0){
pc[_39.target]=new Array();
}
for(var j=0;j<_3e.length;j++){
if((_3d=="P"&&_3e[j].nodeType==3)||(i!=0&&j==0)){
this.fdser.newpg(pc[_39.target]);
}
this.fdser.serializeNode(_3e[j],pc[_39.target],null,_39.inlineDiv?"false":"true");
_3d=_3e[j].nodeName;
}
}
}
var _3f=0;
var _40=new String("");
for(var key in pc){
_40+="<subcontent content_id=\""+key+"\"><paragraph>";
for(var i=0;i<pc[key].length;i++){
_40+=pc[key][i];
_3f++;
}
_40+="</paragraph></subcontent>";
}
if(_3f==0){
return null;
}else{
if(this.clt.getFDDebug()){
alert("ContentCount : ["+_3f+"]");
}
}
return "<content>"+_40+"</content>";
};
this.getXPathNodes=function(_42){
var rv=new Array();
try{
var _44=document.evaluate(_42.query,document,null,XPathResult.UNORDERED_NODE_ITERATOR_TYPE,null);
var _45=_44.iterateNext();
if(_42.occurrence=="once"){
if(_45){
if(_42.include=="outer"){
rv.push(_45);
}else{
if(_42.include=="inner"){
var _46=_45.childNodes;
var _47="";
for(var j=0;j<_46.length;j++){
rv.push(_46[j]);
}
}
}
}else{
}
}else{
if(_42.occurrence=="all"){
while(_45){
if(_42.include=="outer"){
rv.push(_45);
}else{
if(_42.include=="inner"){
var _46=_45.childNodes;
var _47="";
for(var j=0;j<_46.length;j++){
rv.push(_46[j]);
}
}
}
_45=_44.iterateNext();
}
}
}
}
catch(e){
return null;
}
return rv;
};
this.sortXPathDefs=function(_49){
var _4a=new Array();
var _4b=new Array();
var _4c=new Array();
var _4d=new Array();
for(var i=0;i<_49.length;i++){
var _4f=_49[i];
if(_4f.selection=="exclude"){
_4a.push(_4f);
}else{
if(_4f.location=="front"){
_4b.push(_4f);
}else{
if(_4f.location=="domOrder"){
_4c.push(_4f);
}else{
if(_4f.location=="back"){
_4d.push(_4f);
}
}
}
}
}
return _4a.concat(_4b.concat(_4c.concat(_4d)));
};
this.replacePrintLinks=function(){
this.xpathDefs=FDCPLoader.cpDef.xpathDefs;
var _50=new Array();
for(var i=0;i<this.xpathDefs.length;i++){
var _52=this.xpathDefs[i];
if(_52.selection=="printlink"){
var _53=this.getXPathNodes(_52);
if(_53==null){
return null;
}
for(var j=0;j<_53.length;j++){
_50.push(_53[j]);
}
}
}
for(var i=0;i<_50.length;i++){
var _55=_50[i];
if(_55.nodeName=="A"){
_55.href="#";
_55.onclick=function(){
FDCPUrl();
return false;
};
}else{
if(_55.nodeName=="BUTTON"){
_55.onclick=function(){
FDCPUrl();
return false;
};
}
}
}
};
this.getImages=function(){
var _56=new Array();
var _57="<images>";
if(typeof fdImages!="undefined"){
for(var n=0;n<fdImages.length;n++){
var img=document.getElementById(fdImages[n]);
if(img!=null){
this.fdser.serializeNode(img,_56,false);
}
}
for(var i=0;i<_56.length;i++){
_57+=_56[i];
}
}
_57+="</images>";
return _57;
};
this.enPt=function(_5b){
if(typeof formatDynamicsPT!="undefined"){
for(i=0;i<document.styleSheets.length;i++){
try{
var _5c=document.styleSheets[i];
if(navigator.appName.indexOf("Netscape")!=-1&&formatDynamicsPT.isPtCss(_5c.cssRules[0].style.content)){
_5c.disabled=!_5b;
break;
}
}
catch(e){
}
}
}
};
this.CPFailover=function(_5d){
this.linkClicked=false;
if(_5d==false){
if(this.pfLink!=null){
var _5e=this.clt.getCfg("pfType",null);
if(_5e==null||_5e.toLowerCase()=="replace"){
window.open(this.pfLink,"_self");
return false;
}else{
window.open(this.pfLink);
return false;
}
}
window.print();
}
};
this.browserDetect={init:function(){
this.browser=this.searchString(this.dataBrowser)||"An unknown browser";
this.version=this.searchVersion(navigator.userAgent)||this.searchVersion(navigator.appVersion)||"an unknown version";
this.OS=this.searchString(this.dataOS)||"an unknown OS";
},searchString:function(_5f){
for(var i=0;i<_5f.length;i++){
var _61=_5f[i].string;
var _62=_5f[i].prop;
this.versionSearchString=_5f[i].versionSearch||_5f[i].identity;
if(_61){
if(_61.indexOf(_5f[i].subString)!=-1){
return _5f[i].identity;
}
}else{
if(_62){
return _5f[i].identity;
}
}
}
},searchVersion:function(_63){
var _64=_63.indexOf(this.versionSearchString);
if(_64==-1){
return;
}
return parseFloat(_63.substring(_64+this.versionSearchString.length+1));
},dataBrowser:[{string:navigator.userAgent,subString:"OmniWeb",versionSearch:"OmniWeb/",identity:"OmniWeb"},{string:navigator.vendor,subString:"Apple",identity:"Safari"},{prop:window.opera,identity:"Opera"},{string:navigator.vendor,subString:"iCab",identity:"iCab"},{string:navigator.vendor,subString:"KDE",identity:"Konqueror"},{string:navigator.userAgent,subString:"Firefox",identity:"Firefox"},{string:navigator.vendor,subString:"Camino",identity:"Camino"},{string:navigator.userAgent,subString:"Netscape",identity:"Netscape"},{string:navigator.userAgent,subString:"MSIE",identity:"Explorer",versionSearch:"MSIE"},{string:navigator.userAgent,subString:"Gecko",identity:"Mozilla",versionSearch:"rv"},{string:navigator.userAgent,subString:"Mozilla",identity:"Netscape",versionSearch:"Mozilla"}],dataOS:[{string:navigator.platform,subString:"Win",identity:"Windows"},{string:navigator.platform,subString:"Mac",identity:"Mac"},{string:navigator.platform,subString:"Linux",identity:"Linux"}]};
this.loadHandler=function(){
var _65=this.browserDetect.browser=="Explorer"&&this.browserDetect.OS=="Windows";
if(!this.cpEn()){
if(_65&&this.browserDetect.version>=4&&this.clt.insType=="c"){
window.attachEvent("onbeforeprint",function(){
fdcp.fdpt.getFDImage();
});
}
}else{
fdcp.bridge.loadHandler(this);
}
if(!_65&&this.clt.insType=="c"){
fdcp.fdpt.changePrintStyleSheet();
}
};
this.browserDetect.init();
}
var fdcp=new FDCP();
if(typeof FDCPLoader!="undefined"){
fdcp.loadHandler();
}else{
if(window.addEventListener){
window.addEventListener("load",function(){
fdcp.loadHandler();
},false);
}else{
if(window.attachEvent){
window.attachEvent("onload",function(){
fdcp.loadHandler();
});
}
}
}
FDCPLoader.registerModuleLoaded("cp.js");
function FDSerializer(_66){
this.fdclient=_66;
this._bxs="border-style";
this._bbs="border-bottom-style";
this._bts="border-top-style";
this._bls="border-left-style";
this._brs="border-right-style";
this._bxw="border-width";
this._bbw="border-bottom-width";
this._btw="border-top-width";
this._blw="border-left-width";
this._brw="border-right-width";
this._bxc="border-color";
this._bbc="border-bottom-color";
this._btc="border-top-color";
this._blc="border-left-color";
this._brc="border-right-color";
this._ffam="font-family";
this._fsiz="font-size";
this._fwei="font-weight";
this._fsty="font-style";
this._fcol="color";
this._bgc="background-color";
this._bgi="background-image";
this._bgr="background-repeat";
this._mta="text-align";
this._brcl="border-collapse";
this._brsp="border-spacing";
this._px="padding";
this._pb="padding-bottom";
this._pt="padding-top";
this._pl="padding-left";
this._pr="padding-right";
this._clear="clear";
this._float="float";
this._mb="margin-bottom";
this._mt="margin-top";
this.sm=new Array();
this.sm[this._bxs]="borderStyle";
this.sm[this._bbs]="borderBottomStyle";
this.sm[this._bts]="borderTopStyle";
this.sm[this._bls]="borderLeftStyle";
this.sm[this._brs]="borderRightStyle";
this.sm[this._bxw]="borderWidth";
this.sm[this._bbw]="borderBottomWidth";
this.sm[this._btw]="borderTopWidth";
this.sm[this._blw]="borderLeftWidth";
this.sm[this._brw]="borderRightWidth";
this.sm[this._bxc]="borderColor";
this.sm[this._bbc]="borderBottomColor";
this.sm[this._btc]="borderTopColor";
this.sm[this._blc]="borderLeftColor";
this.sm[this._brc]="borderRightColor";
this.sm[this._ffam]="fontFamily";
this.sm[this._fsiz]="fontSize";
this.sm[this._fwei]="fontWeight";
this.sm[this._fsty]="fontStyle";
this.sm[this._fcol]="color";
this.sm[this._clear]="clear";
this.sm[this._float]="float";
this.sm[this._bgc]="backgroundColor";
this.sm[this._bgi]="backgroundImage";
this.sm[this._bgr]="backgroundRepeat";
this.sm[this._mta]="textAlign";
this.sm[this._brcl]="borderCollapse";
this.sm[this._brsp]="borderSpacing";
this.sm[this._px]="padding";
this.sm[this._pb]="paddingBottom";
this.sm[this._pt]="paddingTop";
this.sm[this._pl]="paddingLeft";
this.sm[this._pr]="paddingRight";
this.sm[this._mb]="marginBottom";
this.sm[this._mt]="marginTop";
this.sz=new Array();
this.sz["xx-small"]="8pt";
this.sz["x-small"]="10pt";
this.sz["small"]="12pt";
this.sz["medium"]="14pt";
this.sz["large"]="18pt";
this.sz["x-large"]="24pt";
this.sz["xx-large"]="35pt";
this.sz["auto"]="10pt";
this.ftsz=new Array();
this.ftsz[1]="10px";
this.ftsz[2]="12px";
this.ftsz[3]="14px";
this.ftsz[4]="18px";
this.ftsz[5]="24px";
this.ftsz[6]="30px";
this.ftsz[7]="48px";
this._widestblkwidth=0;
this.excludesXpath=new Array();
this.text_only_state={off:0,on:1,once:2};
this.getHeadingLevel=function(_67){
if(_67=="H1"){
return "24pt";
}else{
if(_67=="H2"){
return "18pt";
}else{
if(_67=="H3"){
return "14pt";
}else{
if(_67=="H4"){
return "12pt";
}else{
if(_67=="H5"){
return "10pt";
}else{
if(_67=="H6"){
return "8pt";
}else{
return "12pt";
}
}
}
}
}
}
};
this.translateStyle=function(_68){
if(_68=="float"){
if(fdcp.browserDetect.browser=="Explorer"){
return "styleFloat";
}else{
return "cssFloat";
}
}
var v=this.sm[_68];
if(v){
return v;
}
return _68;
};
this.isMT=function(val){
return val==null||typeof val=="undefined"||val=="";
};
this.isRelFont=function(sz){
return sz.indexOf("%")>0||sz.indexOf("em")>0||sz.indexOf("ex")>0;
};
this.getStyleValue=function(_6c,_6d,_6e){
var _6f=this.translateStyle(_6d);
if(typeof _6e!="undefined"&&_6e==true){
if(_6c.style[_6f].length>0){
return _6c.style[_6f];
}else{
return null;
}
}
if(_6d=="width"&&_6c.offsetWidth){
return _6c.offsetWidth;
}
if(_6d=="height"&&(typeof _6c.offsetHeight!="undefined"&&_6c.offsetHeight!=null)){
if(fdcp.browserDetect.browser=="Firefox"&&fdcp.browserDetect.version==2&&_6c.nodeName=="SPAN"){
var _70=typeof _6c.offsetHeight!="undefined"&&_6c.offsetHeight!=null?_6c.offsetHeight:0;
for(var i=0;i<_6c.childNodes.length;i++){
if(_6c.childNodes[i].nodeType!=3){
_70+=this.getStyleValue(_6c.childNodes[i],"height");
}
}
return _70;
}else{
if(_6c.offsetHeight==0){
if(_6c.childNodes.length==1){
if(typeof this.getStyleValue(_6c.childNodes[0],"float")!="undefined"&&this.getStyleValue(_6c.childNodes[0],"float")!=null){
var _72=this.getStyleValue(_6c.childNodes[0],"float");
var _73=0;
if(_72=="left"){
_73=this.getStyleValue(_6c.childNodes[0],"height");
return _73;
}
}
}
return _6c.offsetHeight;
}else{
var _74=_6c.offsetHeight;
if(_6c.nodeName=="DIV"||_6c.nodeName=="TD"||_6c.nodeName=="TH"){
var _75=this.getStyleValue(_6c,this._pt);
var _76=this.getStyleValue(_6c,this._pb);
if(_75.indexOf("px")!=-1){
_74-=_75.substring(0,_75.length-2);
}
if(_76.indexOf("px")!=-1){
_74-=_76.substring(0,_76.length-2);
}
}
return _74;
}
}
return _6c.offsetHeight;
}
if(_6c.currentStyle&&fdcp.browserDetect.browser=="Explorer"){
var _77=_6c.currentStyle[_6f];
if(_6d==this._fsiz&&_77.match(/^\d+$/)!=null){
if(_77<1){
_77=1;
}else{
if(_77>7){
_77=7;
}
}
_77=this.ftsz[_77];
}
return _77;
}else{
try{
var _78=document.defaultView.getComputedStyle(_6c,"");
var ret=_78[_6f];
return ret;
}
catch(e){
if(_6d!=this._float){
try{
var _78=document.defaultView.getComputedStyle(_6c.parentNode,"");
var ret=_78[_6f];
return ret;
}
catch(e2){
var _7a=_6c.parentNode.currentStyle[_6f];
if(fdcp.browserDetect.browser=="Explorer"&&_6d==this._fsiz&&_7a.match(/^\d+$/)!=null){
_7a=this.getIeFtSz(_7a);
}
return _7a;
}
}else{
return null;
}
}
}
};
this.getIeFtSz=function(_7b){
if(_7b<1){
_7b=1;
}else{
if(_7b>7){
_7b=7;
}
}
return this.ftsz[_7b];
};
this.getBorderStyles=function(_7c){
var bxs,bbs,bts,bls,brs;
var bxw,bbw,btw,blw,brw;
var bxc,bbc,btc,blc,brc;
bxs=this.getStyleValue(_7c,this._bxs);
bbs=this.getStyleValue(_7c,this._bbs);
bts=this.getStyleValue(_7c,this._bts);
bls=this.getStyleValue(_7c,this._bls);
brs=this.getStyleValue(_7c,this._brs);
var _8c=bbs||bts||bls||brs;
if(!bxs&&!_8c){
return "";
}
var _8d="";
if(_8c&&!(bbs==bts&&bts==bls&&bls==brs)){
if(bbs&&bbs!=null&&bbs.length>0){
_8d+=this._bbs+":"+bbs+";";
}
if(bts&&bts!=null&&bts.length>0){
_8d+=this._bts+":"+bts+";";
}
if(bls&&bls!=null&&bls.length>0){
_8d+=this._bls+":"+bls+";";
}
if(brs&&brs!=null&&brs.length>0){
_8d+=this._brs+":"+brs+";";
}
}else{
if(_8c=="none"){
return "";
}else{
if(_8c&&(bbs==bts&&bts==bls&&bls==brs)){
_8d+=this._bxs+":"+bbs+";";
}else{
if(bxs&&bxs!=null&&bxs.length>0){
_8d+=this._bxs+":"+bxs+";";
}
}
}
}
bxw=this.getStyleValue(_7c,this._bxw);
bbw=this.getStyleValue(_7c,this._bbw);
btw=this.getStyleValue(_7c,this._btw);
blw=this.getStyleValue(_7c,this._blw);
brw=this.getStyleValue(_7c,this._brw);
var _8e=bbw||btw||blw||brw;
bxc=this.getStyleValue(_7c,this._bxc);
bbc=this.getStyleValue(_7c,this._bbc);
btc=this.getStyleValue(_7c,this._btc);
blc=this.getStyleValue(_7c,this._blc);
brc=this.getStyleValue(_7c,this._brc);
var _8f=bbc||btc||blc||brc;
if(_8e&&!(bbw==btw&&btw==blw&&blw==brw)){
if(bbw&&bbw!=null&&bbw.length>0){
_8d+=this._bbw+":"+bbw+";";
}
if(btw&&btw!=null&&btw.length>0){
_8d+=this._btw+":"+btw+";";
}
if(blw&&blw!=null&&blw.length>0){
_8d+=this._blw+":"+blw+";";
}
if(brw&&brw!=null&&brw.length>0){
_8d+=this._brw+":"+brw+";";
}
}else{
if(_8e&&(bbw==btw&&btw==blw&&blw==brw)){
_8d+=this._bxw+":"+bbw+";";
}else{
if(bxw&&bxw!=null&&bxw.length>0){
_8d+=this._bxw+":"+bxw+";";
}
}
}
if(_8f&&!(bbc==btc&&btc==blc&&blc==brc)){
if(bbc&&bbc!=null&&bbc.length>0){
_8d+=this._bbc+":"+bbc+";";
}
if(btc&&btc!=null&&btc.length>0){
_8d+=this._btc+":"+btc+";";
}
if(blc&&blc!=null&&blc.length>0){
_8d+=this._blc+":"+blc+";";
}
if(brc&&brc!=null&&brc.length>0){
_8d+=this._brc+":"+brc+";";
}
}else{
if(_8f&&(bbc==btc&&btc==blc&&blc==brc)){
_8d+=this._bxc+":"+bbc+";";
}else{
if(bxc&&bxc!=null&&bxc.length>0){
_8d+=this._bxc+":"+bxc+";";
}
}
}
return _8d;
};
this.getPaddingStyle=function(_90){
var px,pb,pt,pl,pr;
px=this.getStyleValue(_90,this._px);
pb=this.getStyleValue(_90,this._pb);
pt=this.getStyleValue(_90,this._pt);
pl=this.getStyleValue(_90,this._pl);
pr=this.getStyleValue(_90,this._pr);
var _96=pb||pt||pl||pr;
if(!px&&!_96){
return "";
}
var _97="";
if(_96&&!(pb==pt&&pt==pl&&pl==pr)){
if(pb&&pb!=null&&pb.length>0){
_97+=this._pb+":"+pb+";";
}
if(pt&&pt!=null&&pt.length>0){
_97+=this._pt+":"+pt+";";
}
if(pl&&pl!=null&&pl.length>0){
_97+=this._pl+":"+pl+";";
}
if(pr&&pr!=null&&pr.length>0){
_97+=this._pr+":"+pr+";";
}
}else{
if(_96=="none"){
return "";
}else{
if(_96&&(pb==pt&&pt==pl&&pl==pr)){
_97+=this._px+":"+pb+";";
}else{
if(px&&px!=null&&px.length>0){
_97+=this._px+":"+px+";";
}
}
}
}
return _97;
};
this.getMultiplier=function(str){
if(str.indexOf("%")>0){
var num=str.substring(0,str.indexOf("%"));
return num/100;
}
if(str.indexOf("em")>0){
var num=str.substring(0,str.indexOf("em"));
return num;
}
if(str.indexOf("ex")>0){
var num=str.substring(0,str.indexOf("ex"));
return num*0.4;
}
};
this.calculateFontSize=function(_9a){
var _9b=new Array();
var _9c;
var _9d;
_9b.push(this.getStyleValue(_9a,"font-size"));
_9a=_9a.parentNode;
while(_9a!=null){
if(_9a.nodeType==3){
_9a=_9a.parentNode;
continue;
}
_9d=this.getStyleValue(_9a,"font-size",true);
if(_9d!=null){
if(this.isRelFont(_9d)){
_9b.push(_9d);
}else{
break;
}
}else{
_9d=this.getStyleValue(_9a,"font-size");
if(this.isRelFont(_9d)==false){
break;
}else{
if(_9d!=_9b[_9b.length-1]){
_9b.push(_9d);
}
}
}
_9a=_9a.parentNode;
}
if(this.sz[_9d]!=null){
_9d=this.sz[_9d];
}
_9c=_9d.substring(_9d.length-2);
_9d=_9d.substring(0,_9d.length-2);
for(var i=0;i<_9b.length;i++){
_9d=_9d*this.getMultiplier(_9b[i]);
}
return Math.round(_9d)+_9c;
};
this.getImageStyle=function(_9f){
var _a0=_9f.width;
var _a1=_9f.height;
var _a2=this.getBorderStyles(_9f);
var _a3;
if(!_a0){
_a0=this.getStyleValue(_9f,"width");
}
if(!_a1){
_a1=this.getStyleValue(_9f,"height");
}
_a3="style=\""+"width:"+_a0+";height:"+_a1+";";
if(typeof this.getStyleValue(_9f,"float")!="undefined"&&this.getStyleValue(_9f,"float")!=null){
_a3+="float:"+this.getStyleValue(_9f,"float")+";";
}
if(_a2){
_a3+=_a2;
}
_a3+="\"";
return _a3;
};
this.getNodeStyle=function(_a4,blk,_a6){
var _a7="style=\"";
var _a8=this.getStyleValue(_a4,this._ffam);
var _a9=this.getStyleValue(_a4,this._fsiz);
var _aa=this.getStyleValue(_a4,this._fwei);
var _ab=this.getStyleValue(_a4,this._fsty);
var _ac=this.getStyleValue(_a4,this._fcol);
if(_a8.indexOf("\"")>=0){
_a8=_a8.replace(/\"/g,"");
}
if(this.isRelFont(_a9)){
_a9=this.calculateFontSize(_a4);
}
_a7+=this._ffam+":"+_a8+";"+this._fsiz+":"+_a9+";"+this._fwei+":"+_aa+";"+this._fsty+":"+_ab+";"+this._fcol+":"+_ac+";";
if(blk){
var _ad=this.getStyleValue(_a4,"width",_a4.nodeName=="SPAN"?true:false);
var _ae=this.getStyleValue(_a4,"height",_a4.nodeName=="SPAN"?true:false);
var _af=this.getBorderStyles(_a4);
var _b0=this.getPaddingStyle(_a4);
var _b1=this.getStyleValue(_a4,this._mta);
var _b2=this.getStyleValue(_a4,this._bgc);
var _b3=this.getStyleValue(_a4,this._bgi).replace(/\"/g,"'");
var _b4=this.getStyleValue(_a4,this._bgr);
var _b5=this.getStyleValue(_a4,this._clear);
var _b6=this.getStyleValue(_a4,this._float);
_a7+=this._clear+":"+_b5+";"+(_b6=="left"||_b6=="right"?(this._float+":"+_b6+";"):"");
_a7+=_af+_b0;
if(_ad!=null&&_ad!="auto"&&_ad!="0auto"){
_a7+="width: "+_ad+";";
}
if(_ae!=null&&_ae!="auto"&&_ae!="0auto"){
if(_a6&&_a6>0&&(_a4.nodeName=="TD"||_a4.nodeName=="TH")){
_ae-=_a6;
}
_a7+="height: "+_ae+";";
}
var mt=this.getStyleValue(_a4,this._mt);
var mb=this.getStyleValue(_a4,this._mb);
if(mt!="auto"&&(mt.charAt(0)=="0"||mt.charAt(0)=="-")){
_a7+=this._mt+":"+mt+";";
}
if(mb!="auto"&&(mb.charAt(0)=="0"||mb.charAt(0)=="-")){
_a7+=this._mb+":"+mb+";";
}
if(_a4.nodeName=="UL"||_a4.nodeName=="LI"){
_a7+="line-height: 1.2;";
if(_a4.nodeName=="UL"){
var _b9="";
if(_a4.currentStyle){
_b9=_a4.currentStyle["listStyleType"];
_a7+="list-style-type:"+_b9+";";
}else{
if(window.getComputedStyle){
_b9=document.defaultView.getComputedStyle(_a4,null).getPropertyValue("list-style-type");
_a7+="list-style-type:"+_b9+";";
}
}
}
}else{
var _ba=this.getStyleValue(_a4,"lineHeight");
if(_ba!="auto"){
_a7+="line-height:"+_ba+";";
}
}
if(_b2!="transparent"){
_a7+=this._bgc+":"+_b2+";";
}
if(_b3!="none"){
if(fdcp.browserDetect.browser=="Firefox"){
var _bb=_b3.replace("url(","url('");
var _bc=_bb.replace(")","')");
_b3=_bc;
_a7+=this._bgi+":"+_b3+";";
}else{
_a7+=this._bgi+":"+_b3+";";
}
}
if(_b4!="repeat"){
_a7+=this._bgr+":"+_b4+";";
}
_a7+=this._mta+":"+_b1+";";
if(_a4.nodeName=="TABLE"){
var v=this.getStyleValue(_a4,this._brcl);
if(!this.isMT(v)){
_a7+=this._brcl+":"+v+";";
}
v=this.getStyleValue(_a4,this._brsp);
if(!this.isMT(v)){
_a7+=this._brsp+":"+v+";";
}
}
}
return _a7+"\"";
};
this.serializeText=function(_be){
if(_be.nodeValue&&_be.nodeValue.match(/^\s*$/)==null){
var st=this.getNodeStyle(_be,false);
return "<text "+st+">"+C2E(_be.nodeValue)+"</text>";
}
return "";
};
function C2E(str){
var acc="";
for(var i=0;i<str.length;i++){
if(str.charCodeAt(i)>31&&str.charCodeAt(i)<127){
acc+=str.charAt(i);
}else{
acc+="&#"+str.charCodeAt(i)+";";
}
}
acc=acc.replace(/&/g,"&#38;");
acc=acc.replace(/'/g,"&#39;");
acc=acc.replace(/"/g,"&#34;");
acc=acc.replace(/\\/g,"&#92;");
acc=acc.replace(/\+/g,"&#43;");
acc=acc.replace(/</g,"&#60;");
acc=acc.replace(/>/g,"&#62;");
return acc;
}
this.serializeCDATA=function(_c3){
return "<![CDATA["+_c3.nodeValue+"]]>";
};
this.serializeBR=function(_c4){
return "<text "+this.getNodeStyle(_c4,false)+"><"+_c4.nodeName+" /></text>";
};
this.serializeImage=function(_c5){
var _c6;
var st=this.getImageStyle(_c5,true);
_c6="<image ";
if(_c5.id){
_c6+="id='"+_c5.id+"' ";
}
var i=new Image();
i.src=_c5.getAttribute("src");
return _c6+st+" src='"+C2E(i.src)+"' />";
};
this.serializeParagraph=function(_c9){
return this.serializeBR(_c9);
};
this.serializeGoogleImage=function(_ca){
var _cb;
_cb="<image ";
if(_ca.id){
_cb+="id='"+_ca.id+"' ";
}
var i=new Image();
i.src=_ca.getAttribute("src");
var st=_ca.style.cssText;
return _cb+" style=' "+st+" ' "+" src=' "+C2E(i.src)+" ' />";
};
this.serializeGoogleMapElement=function(_ce,_cf){
var _d0="";
if(_ce.nodeName=="SCRIPT"){
return _d0;
}
var to=_cf;
if(_cf==this.text_only_state.once){
to=this.text_only_state.off;
}
if(_ce.nodeName.charAt(0)=="/"){
return _d0;
}
if(_cf==this.text_only_state.off&&_ce.nodeName!=""){
_d0="<"+_ce.nodeName+" ";
if(_ce.id){
_d0+="id=\""+_ce.id+"\" ";
}
if(_ce.className){
_d0+="class=\""+_ce.className+"\" ";
}
if(_ce.nodeName=="TABLE"){
if(!this.isMT(_ce.border)){
_d0+="border=\""+_ce.border+"\" ";
}
if(!this.isMT(_ce.cellPadding)){
_d0+="cellpadding=\""+_ce.cellPadding+"\" ";
}
if(!this.isMT(_ce.cellSpacing)){
_d0+="cellspacing=\""+_ce.cellSpacing+"\" ";
}
}else{
if(_ce.nodeName=="TD"){
if(!this.isMT(_ce.colSpan)){
_d0+="colspan=\""+_ce.colSpan+"\" ";
}
if(!this.isMT(_ce.rowSpan)){
_d0+="rowspan=\""+_ce.rowSpan+"\" ";
}
if(!this.isMT(_ce.noWrap)){
_d0+="nowrap=\""+_ce.noWrap+"\" ";
}
if(!this.isMT(_ce.vAlign)){
_d0+="valign=\""+_ce.vAlign+"\" ";
}
}
}
var _d2=this.getStyleValue(_ce,"width");
var _d3=this.getStyleValue(_ce,"height");
var _d4="";
if(_d2!=null&&_d2!="auto"&&_d2!="0auto"){
_d4+="width: "+_d2+";";
}
if(_d3!=null&&_d3!="auto"&&_d3!="0auto"){
_d4+="height: "+_d3+";";
}
if(_ce.style.cssText!=null){
if(_ce.nodeName=="SPAN"){
if(_ce.className=="gmnoprint"){
_d0+="style=\""+"position:absolute;height:30px;width:62px;-moz-user-select: none; position: absolute; left: 2px; bottom: 2px;"+"\" >";
}else{
_d0+=this.getNodeStyle(_ce,true)+">";
}
}else{
if(_ce.id=="mapcontainer"){
_d0+="style=\""+"position:relative;"+_d4;
var f=this.getStyleValue(_ce,"float");
if(f=="left"||f=="right"){
_d0+="float:"+f+";\">";
}
}else{
if(fdcp.browserDetect.browser=="Explorer"){
var _d2=_ce.currentStyle["width"];
if(_d2=="100%"){
var _d6=_ce.parentNode.currentStyle["width"];
_ce.style.width=_d6;
}
_d0+="style=\""+_ce.style.cssText+";"+"\" >";
}else{
_d0+="style=\""+_ce.getAttribute("style")+_d4+"\" >";
}
}
}
}else{
_d0+=this.getNodeStyle(_ce,true)+">";
}
}
for(var i=0;i<_ce.childNodes.length;i++){
var _d8=_ce.childNodes[i];
if(this.isExcluded(_d8)){
continue;
}
if(_d8.nodeType==3){
_d0+=this.serializeText(_d8);
}else{
if(_d8.nodeType==4){
_d0+=this.serializeCDATA(_d8);
}else{
if(_d8.nodeType==1){
if(_d8.nodeName=="BR"){
_d0+=this.serializeBR(_d8);
}else{
if(_d8.nodeName=="P"){
_d0+=this.serializeInlineElement(_d8,this.text_only_state.off);
}else{
if(_d8.nodeName=="IMG"||_ce.nodeName=="IMAGE"){
_d0+=this.serializeGoogleImage(_d8);
}else{
_d0+=this.serializeGoogleMapElement(_d8,this.text_only_state.off);
}
}
}
}
}
}
}
if(_cf==this.text_only_state.off&&_ce.nodeName!=""){
_d0+="</"+_ce.nodeName+">";
}
return _d0;
};
this.serializeInlineElement=function(_d9,_da,_db){
if(typeof _db=="undefined"){
_db=null;
}
var _dc="";
if(_d9.nodeName=="SCRIPT"){
return _dc;
}
var _dd=this.getStyleValue(_d9,"height");
var to=_da;
if(_da==this.text_only_state.once){
to=this.text_only_state.off;
}
if(_d9.nodeName.charAt(0)=="/"){
return _dc;
}
if(parseInt(this.getStyleValue(_d9,"width"))>0){
var _df=this.getStyleValue(_d9,"width");
if(parseInt(_df)>this.getWidestBlkWidth()){
this.setWidestBlkWidth(_df);
}
}
if(_da==this.text_only_state.off&&_d9.nodeName!=""){
_dc="<"+_d9.nodeName+" ";
if(_d9.id){
_dc+="id=\""+_d9.id+"\" ";
}
if(_d9.type){
_dc+="type=\""+_d9.type+"\" ";
}
if(_d9.className){
_dc+="class=\""+_d9.className+"\" ";
}
if(_d9.checked){
_dc+="checked=\""+_d9.checked+"\" ";
}
if(_d9.nodeName=="TABLE"){
if(!this.isMT(_d9.border)){
_dc+="border=\""+_d9.border+"\" ";
}
if(!this.isMT(_d9.cellPadding)){
_dc+="cellpadding=\""+_d9.cellPadding+"\" ";
_db=_d9.cellPadding;
}
if(!this.isMT(_d9.cellSpacing)){
_dc+="cellspacing=\""+_d9.cellSpacing+"\" ";
}
}else{
if(_d9.nodeName=="TD"){
if(!this.isMT(_d9.colSpan)){
_dc+="colspan=\""+_d9.colSpan+"\" ";
}
if(!this.isMT(_d9.rowSpan)){
_dc+="rowspan=\""+_d9.rowSpan+"\" ";
}
if(!this.isMT(_d9.noWrap)){
_dc+="nowrap=\""+_d9.noWrap+"\" ";
}
if(!this.isMT(_d9.vAlign)){
_dc+="valign=\""+_d9.vAlign+"\" ";
}
}
}
_dc+=this.getNodeStyle(_d9,true,_db)+">";
}
for(var i=0;i<_d9.childNodes.length;i++){
var _e1=_d9.childNodes[i];
if(this.isExcluded(_e1)){
continue;
}
if(_e1.nodeName=="DIV"&&_e1.id=="mapcontainer"){
_dc+=this.serializeGoogleMapElement(_e1,to);
}else{
if(this.getStyleValue(_e1,"display")=="none"){
continue;
}
if(_e1.nodeType==3){
_dc+=this.serializeText(_e1);
}else{
if(_e1.nodeType==4){
_dc+=this.serializeCDATA(_e1);
}else{
if(_e1.nodeType==1){
if(_e1.nodeName=="BR"){
_dc+=this.serializeBR(_e1);
}else{
if(_e1.nodeName=="P"){
_dc+=this.serializeInlineElement(_e1,to);
}else{
if(_e1.nodeName=="IMG"||_d9.nodeName=="IMAGE"){
_dc+=this.serializeImage(_e1);
}else{
_dc+=this.serializeInlineElement(_e1,to,_db);
}
}
}
}
}
}
}
}
if(_da==this.text_only_state.off&&_d9.nodeName!=""){
_dc+="</"+_d9.nodeName+">";
}
return _dc;
};
this.newpg=function(_e2){
_e2.push("</paragraph><paragraph>");
};
this._serNode=function(_e3,_e4,_e5){
var v;
var _e7=false;
if(this.isExcluded(_e3)){
return;
}
if(this.getStyleValue(_e3,"display")=="none"){
return;
}
var _e8=this.getStyleValue(_e3,"height");
if((typeof _e8=="undefined"||_e8==null||_e8==0)&&(_e3.nodeName!="BR")){
return;
}
if(typeof _e5=="undefined"||_e5==null){
_e5=this.text_only_state.off;
}
if(_e3.nodeType==3){
v=this.serializeText(_e3);
if(v!=null&&v.length>0){
_e4.push(v);
}
}else{
if(_e3.nodeType==4){
_e4.push(this.serializeCDATA(_e3));
}else{
if(_e3.nodeType==1){
if(_e3.nodeName=="SCRIPT"){
return;
}
if(_e3.nodeName=="BR"){
_e4.push(this.serializeBR(_e3));
}else{
if(_e3.nodeName=="IMG"||_e3.nodeName=="IMAGE"){
_e4.push(this.serializeImage(_e3));
}else{
if(_e3.nodeName=="P"){
if(_e4.length>0){
this.newpg(_e4);
}
}else{
if(_e3.nodeName=="TR"){
this.newpg(_e4);
}else{
if(_e3.nodeName.match(/^H\d$/)!=null){
_e7=true;
this.newpg(_e4);
}
}
}
var _e9=_e3.offsetWidth;
var _ea=new Boolean(true);
var _eb=this.fdclient.getBlockThreshold();
if((typeof _e9!="undefined"&&_e9!=null&&_e9>_eb)||_e5>0){
_ea=false;
}
if(((_e3.nodeName=="DIV"&&_ea)||_e3.nodeName=="TABLE"&&_e5!=this.text_only_state.on)||_e3.nodeName=="UL"){
if(_e3.nodeName=="UL"){
_e5=0;
}
if(_e3.nodeName=="DIV"&&_e3.id=="mapcontainer"){
_e4.push(this.serializeGoogleMapElement(_e3,_e5));
}else{
_e4.push(this.serializeInlineElement(_e3,_e5));
}
}else{
try{
if(_e3.nodeName=="SPAN"){
if(_e3.getAttribute("inlineDiv")=="true"){
var _ec=_e3.firstChild;
_e4.push(this.serializeInlineElement(_ec,_e5));
}else{
if(_e3.getAttribute("inlineDiv")=="false"){
this.newpg(_e4);
this._serNode(_e3.nextSibling,_e4,this.text_only_state.on);
}else{
if(_e3.getAttribute("formatdynamics")=="content"){
for(var m=_e3.firstChild;m!=null;m=m.nextSibling){
this._serNode(m,_e4,this.text_only_state.off);
}
}else{
for(var m=_e3.firstChild;m!=null;m=m.nextSibling){
this._serNode(m,_e4,this.text_only_state.off);
}
}
}
}
}else{
if(_e3.nodeName=="DIV"){
if(_e4.length>0){
this.newpg(_e4);
}
}
for(var m=_e3.firstChild;m!=null;m=m.nextSibling){
this._serNode(m,_e4,_e5);
}
}
}
catch(e){
for(var m=_e3.firstChild;m!=null;m=m.nextSibling){
this._serNode(m,_e4,this.text_only_state.once);
}
}
}
if(_e7){
this.newpg(_e4);
}
}
}
}
}
}
};
this.serializeNode=function(_ee,_ef,_f0,_f1){
var _f2=this.text_only_state.off;
if(_f1=="true"){
_f2=this.text_only_state.on;
}
this._serNode(_ee,_ef,_f2);
};
this.isExcluded=function(_f3){
for(var i=0;i<this.excludesXpath.length;i++){
if(_f3==this.excludesXpath[i]){
return true;
}
}
var _f5=this.fdclient.getCfg("excludes");
if(typeof _f5=="undefined"||_f5==null){
return false;
}
var _f6="";
var _f7="";
var id="";
if(typeof _f3.nodeName!="undefined"&&_f3.nodeName!=null){
_f6=_f3.nodeName.toLowerCase();
}
if(typeof _f3.className!="undefined"&&_f3.className!=null){
_f7=_f3.className.toLowerCase();
}
if(typeof _f3.id!="undefined"&&_f3.id!=null){
id=_f3.id.toLowerCase();
}
for(var i=0;i<_f5.length;i++){
var e=_f5[i].toLowerCase();
var _fa=_f7.split(/\s+/);
for(var cl in _fa){
if(_fa[cl]==e||("."+_fa[cl])==e){
return true;
}
if((_f6+"."+_fa[cl])==e){
return true;
}
if(id==e||("#"+id)==e){
return true;
}
if((_f6+"."+id)==e||(_f6+"#"+id)==e){
return true;
}
}
}
return false;
};
this.setExcludes=function(_fc){
this.excludesXpath=_fc;
};
this.getWidestBlkWidth=function(){
return this._widestblkwidth;
};
this.setWidestBlkWidth=function(_fd){
this._widestblkwidth=_fd;
};
}
function FormatDynamicsPT(_fe){
this.clnt=_fe;
this.pcol=document.location.protocol+"//";
this.cstr=_fe.getTHost()+"/pt/t/";
this.dtstr=(new Date()).getTime();
this.div="&d="+this.clnt.getDiv();
this.ua="&a="+escape(navigator.appName+" "+navigator.userAgent);
this.seg="&s="+escape(this.clnt.getSegment());
this.ustr="&u="+escape(window.location.href);
this.pf="&p="+this.clnt.getPFF();
this.version="&q=1.1";
this.rtype="&rt="+this.clnt.getRType();
this.qstr=this.div+this.ua+this.seg+this.ustr+this.pf+this.version;
this.turl=this.pcol+this.cstr+this.dtstr+"?"+this.qstr;
this.pthosts="formatdynamics.com,cleanprint.net";
this.isPtCss=function(_ff){
var _100=this.pthosts.split(",");
for(var i=0;i<_100.length;i++){
if(_ff.indexOf(_100[i])!=-1){
return true;
}
}
return false;
};
this.changePrintStyleSheet=function(){
for(i=0;i<document.styleSheets.length;i++){
try{
var _102=document.styleSheets[i];
var _103=navigator.userAgent.toLowerCase();
if((navigator.appName.indexOf("Netscape")!=-1||_103.indexOf("firefox")!=-1||_103.indexOf("safari")!=-1)&&this.isPtCss(_102.cssRules[0].style.content)){
_102.cssRules[0].style.content="url("+this.turl+");";
return;
}else{
if(this.isPtCss(_102.cssRules[0].style.getPropertyValue("content"))){
if(navigator.appName.indexOf("Opera")!=-1){
_102.deleteRule(0);
}else{
if(navigator.appName.indexOf("Konqueror")==-1){
_102.cssRules[0].style.setProperty("content","url("+this.turl+")",null);
}
}
return;
}
}
}
catch(err){
}
}
try{
var _103=navigator.userAgent.toLowerCase();
if((navigator.appName.indexOf("Netscape")!=-1||_103.indexOf("firefox")!=-1||_103.indexOf("safari")!=-1)){
var s=document.createElement("style");
s.type="text/css";
s.rel="stylesheet";
s.media="print";
s.appendChild(document.createTextNode("body:before {content: url("+this.turl+")};"));
document.getElementsByTagName("head")[0].appendChild(s);
}
}
catch(err){
}
};
this.getFDImage=function(){
if(this.clnt.getRType()=="s"){
var hs=document.documentElement.getElementsByTagName("head");
var h=null;
if(hs&&hs.length>0){
h=hs[0];
var _107=document.createElement("script");
_107.type="text/javascript";
_107.src=this.turl+"&rnd="+Math.random();
}
}else{
var _108=new Image();
_108.src=this.turl;
}
};
}

