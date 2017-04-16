/************************************************
**  AUTHOR: Kevin K. Nelson                    **
**  SITE:   http://www.taoti.com/              **
**  DESCR:  JavaScript for image non-window    **
**          pop-up                             **
**                                             **
** -- Copyright © 2004, All Rights Reserved -- **
************************************************/

///////////////////////////////////////////////////////////////////////////////////////
// TESTED ON OPERA 7.54           (works under IE scheme)
// TESTED ON NETSCAPE 7           (works under NETSCAPE scheme)
// TESTED ON Mozilla FireFox 1.0  (works under NETSCAPE scheme)
// TESTED ON IE 6.0.2             (works under IE scheme)
//
// 12/07/04   - Netscape interprets document.body.offsetWidth && offsetHeight
//              as the whole width and height of the document--including areas
//              off of the screen.  So, had to use window.innerWidth && innerHeight
//              for Netscape only.  IE and Opera both interpret the offset as being
//              the current page.
//
//            - Netscape 7 interprets document.body.scrollLeft && scrollTop correctly.
//              However, I'm unsure that Netscape 6 will be the same.
//
//            - When IE is in Standards Compatibility Mode, there are some issues
//              document.body.scrollTop/scrollLeft will both return 0. You must use
//              document.body.parentNode.scrollTop/scrollLeft for it to work.  I am
//              unsure what causes this...doing the same thing on another script caused
//              errors.
///////////////////////////////////////////////////////////////////////////////////////

var IS_DEBUG_MODE			= false;
function debugWrite( p_strText ) {
	if( IS_DEBUG_MODE ) {
		alert( p_strText );
	}
}

// BROWSER SNIFFING FOR DYNAMIC BROWSER CAPABILITIES
var AGENT					= navigator.userAgent.toLowerCase();
var IS_DYNAMIC_BROWSER      = document.all || document.getElementById;
var IS_IE					= AGENT.indexOf("msie") != -1 && AGENT.indexOf("opera") == -1;
var IS_NETSCAPE             = AGENT.indexOf('mozilla')!=-1 && AGENT.indexOf('spoofer')==-1 && AGENT.indexOf('compatible') == -1 && AGENT.indexOf('opera')==-1 && AGENT.indexOf('webtv')==-1 && AGENT.indexOf('hotjava')==-1;
var IS_COMPAT_MODE			= document.compatMode == "CSS1Compat";

//debugWrite( document.body.parentNode.scrollTop );
if( IS_DYNAMIC_BROWSER ) {
    document.write("<div id='img_container' onclick='javascript:this.style.display=\"none\"' style='display:none;position:absolute;left:0px;top:0px;z-index:1;width:0px;height:0px;'>&nbsp;</div>");
}

function getBody() {
	return( IS_COMPAT_MODE && IS_IE ? document.body.parentNode : document.body );
}
function getTagByID( p_strID ) {
    return( document.all ? document.all[p_strID] : document.getElementById(p_strID) );
}
function centerObjectOnPage( p_strID ) {
	arrPositions					= new Array();
    iPageWidth      				= IS_NETSCAPE ? window.innerWidth  : getBody().offsetWidth;
    iPageHeight     				= IS_NETSCAPE ? window.innerHeight : getBody().offsetHeight;
	iScrollTop						= IS_NETSCAPE ? window.pageYOffset : getBody().scrollTop;
	iScrollLeft						= IS_NETSCAPE ? window.pageXOffset : getBody().scrollLeft;
	
    iLeftPosition   				= (( iPageWidth  - parseInt(getTagByID(p_strID).style.width ) ) / 2) + iScrollLeft;
//	iTopPosition    				= (( iPageHeight - parseInt(getTagByID(p_strID).style.height) ) / 2) + iScrollTop - 160;
	iTopPosition					= 50 + iScrollTop;

	arrPositions['left']			= iLeftPosition;
	arrPositions['top']				= iTopPosition;

    getTagByID(p_strID).style.left  = arrPositions['left'] + "px";
    getTagByID(p_strID).style.top   = arrPositions['top']  + "px";
	
	return( arrPositions );
}
function showImage( p_strURL, p_iWidth, p_iHeight, p_strBasePath ) {
	if( IS_DYNAMIC_BROWSER ) {
		getTagByID('img_container').style.width             = p_iWidth + "px";
		getTagByID('img_container').style.height            = p_iHeight + "px";
		arrLocation											= centerObjectOnPage('img_container');
	
		getTagByID('img_container').innerHTML				= "<img src='" + p_strURL + "' width='" + p_iWidth + "' height='" + p_iHeight + "' />";
	
		getTagByID('img_container').style.display           = "block";
	}
	else {
		alert("We're sorry, but your browser does not meet current JavaScript standards and is unable to pop-up the full-sized image.\n\nPlease upgrade to a current browser:\n - Internet Explorer 5+ (www.microsoft.com)\n - Netscape 7 (www.netscape.com)\n - Opera 7 (www.opera.com)");
	}
}
