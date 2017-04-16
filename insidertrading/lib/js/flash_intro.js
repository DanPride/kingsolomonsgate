/*************************************************
**  AUTHOR: Tim  Nelson                         **
** - - - - - - - - - - - - - - - - - - - - - - -**
**  DESCR:  JavaScript for hiding the <object>  **
**          and <embed> tags from IE            **
** - - - - - - - - - - - - - - - - - - - - - - -**
**  This IE Browser Work-Around is described    **
**  by Adobe at the following URL:              **
**                                              **
**  http://www.adobe.com/devnet/activecontent/  **
**  articles/devletter.html                     **
** - - - - - - - - - - - - - - - - - - - - - - -**
**  Basically all you do is hide the code in    **
**  a JavaScript function and then call that    **
**  function.                                   **
** - - - - - - - - - - - - - - - - - - - - - - -**
**  There are other ways to handle multiple     **
**  instances.  See Adobe for more details.     **
*************************************************/

///////////////////////////////////////////////////////////////////////
// This is just code that would normally be found with the other HTML
function flashIntro()
{
	document.write('<object classid="clsid:D27CDB6E-AE6D-11cf-96B8-444553540000"\n');
	document.write('codebase="http://download.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=5,0,42,0"\n');
	document.write('id="_3d_flash_intro" width="751" height="414">\n');
	document.write('<param name="movie" value="3d_flash_intro.swf">\n');
	document.write('<param name="bgcolor" value="#FFFFFF">\n');
	document.write('<param name="quality" value="high">\n');
	document.write('<param name="allowscriptaccess" value="samedomain">\n');
	document.write('<embed type="application/x-shockwave-flash"\n');
	document.write('pluginspage="http://www.macromedia.com/go/getflashplayer"\n');
	document.write('width="751" height="414"\n');
	document.write('name="_3d_flash_intro" src="3d_flash_intro.swf"\n');
	document.write('bgcolor="#FFFFFF" quality="high"\n');
	document.write('swLiveConnect="true" allowScriptAccess="samedomain"\n');
	document.write('></embed>\n');
	document.write('</object>\n');
}
