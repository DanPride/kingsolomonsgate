// JavaScript Document
//Expandable ticker script- By Dynamic Drive
//For full source code and more DHTML scripts, visit http://www.dynamicdrive.com
//This credit MUST stay intact for use

//configure tickercontents[] to set the messges you wish be displayed (HTML codes accepted)

/*tickercontents[0]='<span class="tickertext"><B>BREAKING NEWS:</B> The first headline can be here. - <a href="" class="tickerlink">Full Story</a></span>'
tickercontents[1]='<span class="tickertext"><B>BREAKING NEWS:</B> Then the second headline. - <a href="" class="tickerlink">Full Story</a></span>'
tickercontents[2]='<span class="tickertext"><B>SCHOOL CLOSINGS:</B> Maybe then a list of school closings? - <a href="" class="tickerlink">Full Closings List</a></span>'
*/

//configure the below 2 variables to set the width/background color of the ticker
var tickerwidth='792px'
var tickerbgcolor='#B63928'
var tickerbgcolorexpand='#E35744'

//configure the below variable to determine the delay between ticking of messages (in miliseconds)
var tickdelay=3000

////Do not edit past this line////////////////

var ie4=document.all
var ns6=document.getElementById
var ns4=document.layers

var currentmessage=0
var tickercontentstotal=''

function changetickercontent(){
if (ns4){
tickerobj.document.tickernssub.document.write('<b><a href="#" onClick="return expandlist(event)">+ Expand</a></b> | '+tickercontents[currentmessage])
tickerobj.document.tickernssub.document.close()
}
else if (ie4||ns6){
tickerobj.innerHTML=tickercontents[currentmessage]
previousmessage=(currentmessage==0)? tickercontents.length-1 : currentmessage-1
tickerexpand_item=ns6? document.getElementById("expand"+currentmessage) : eval("expand"+currentmessage)
tickerexpand_previousitem=ns6? document.getElementById("expand"+previousmessage) : eval("expand"+previousmessage)
tickerexpand_previousitem.className=""
tickerexpand_item.className="expandhighlight"
}

currentmessage=(currentmessage==tickercontents.length-1)? 0 : currentmessage+1
if (tickercontents.length > 1) setTimeout("changetickercontent()",tickdelay)

}

function start_ticking(){
if (tickercontents.length == 0) {return false;}
	
if (ns4) document.tickernsmain.visibility="show"
tickerobj=ie4? tickerlist : ns6? document.getElementById("tickerlist") : ns4? document.tickernsmain : ""
tickerexpandobj=ie4? tickerexpand : ns6? document.getElementById("tickerexpand") : ns4? document.expandlayer : ""

for (i=0;i<tickercontents.length;i++) //get total scroller contents
tickercontentstotal+='<div id="expand'+i+'" stlye="font-weight:bold">&bull; '+tickercontents[i]+'</div>'
if (ie4||ns6)
tickerexpandobj.innerHTML=tickercontentstotal
else{
tickerexpandobj.document.write(tickercontentstotal)
tickerexpandobj.document.close()
}
changetickercontent()
}

function expandlist(e){
if (ie4||ns6){
tickerexpand_parent=ie4? tickerexpand.parentElement : document.getElementById("tickerexpand").parentNode
tickerexpand_parent.style.display=(tickerexpand_parent.style.display=="none")? "" : "none"
}
else{
document.expandlayer.left=e.pageX-e.layerX
document.expandlayer.top= e.pageY-e.layerY+20
document.expandlayer.visibility=(document.expandlayer.visibility=="hide")? "show" : "hide"
return false
}
}

if (tickercontents.length > 0) {
if (ie4||ns6)
document.write('<table border="0" style="padding-top:6px; padding-bottom:6px; width:'+tickerwidth+';text-indent:2px" bgcolor="'+tickerbgcolor+'" cellspacing="0" cellpadding="0"><tr><td width="90%" id="tickerlist" bgcolor="'+tickerbgcolor+'"></td><td width="10%" bgcolor="'+tickerbgcolor+'">')
if (tickercontents.length > 1) {document.write('<div id="listbutton" onClick="expandlist()">+<NOBR>Expand</div>')}
document.write('</td></tr><tr style="display:none"><td id="tickerexpand" width="100%" bgcolor="'+tickerbgcolorexpand+'" colspan="2"> </td></tr></table>')
}
//window.onload=start_ticking