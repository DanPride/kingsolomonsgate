/*************************************************
**  AUTHOR: Kevin K. Nelson                     **
**  SITE:   http://www.flashfiredesigns.com/    **
**  DESCR:  JavaScript for dynamically creating **
**          rollovers                           **
**                                              **
**  Copyright © 2003, All Rights Reserved       **
*************************************************/

// AUTOMATICALLY ASSIGN EVENT HANDLER TO ONLOAD
window.onload                           = init;

// HTML SETTINGS
g_strImgClass                           = "imgButton";      // IMAGE ROLLOVER CLASS NAME OF IMAGES
g_strOver_ext                           = "_over";          // "over" image extension (i.e. imgName_over.jpg & imgName_out.jpg)
g_strOut_ext                            = "_out";           // "out"  image extension (i.e. imgName_over.jpg & imgName_out.jpg)

////////////////////////////////////////////////////////
// init() IS DYNAMICALLY CALLED ON PAGE LOAD.  ANY
// JAVASCRIPT METHODS THAT NEED TO BE CALLED ON PAGE
// LOAD...PUT THOSE METHODS WITHIN init();
function init() {
    createRollovers();
}

////////////////////////////////////////////////////////
// createRollovers() LOOKS THROUGH ALL IMAGE TAGS, &
// IF THE TAG HAS THE CLASS NAME g_strImgClass, THEN
// IT WILL DYNAMICALLY CREATE A ROLLOVER EFFECT AND
// PRELOAD THE _OVER IMAGE.
//
// NOTE: YOU MUST USE A NAMING SCHEME CONSISTENTLY
//   (i.e. imgName_out.jpg & imgName_over.jpg)
// SPECIFY _OUT & _OVER EXTENTIONS IN GLOBAL
// VARIABLES g_strOver_ext & g_strOut_ext
function createRollovers() {
    if( document.getElementsByTagName ) {
        var arrIMGTags                      = document.getElementsByTagName("IMG");
        var arrPreloads                     = new Array();

        for(i=0,j=0;i<arrIMGTags.length;i++) {
            if( arrIMGTags[i].className == g_strImgClass ) {
                // PRELOAD _over IMAGES
                var strSrc                  = arrIMGTags[i].src;
                arrPreloads[j]              = new Image();
                arrPreloads[j++].src        = strSrc.replace( g_strOut_ext, g_strOver_ext );
                
                // ASSIGN EVENT HANDLERS
                arrIMGTags[i].onmouseover   = toggleImgOver;
                arrIMGTags[i].onmouseout    = toggleImgOver;
            }
        }
    }
}

////////////////////////////////////////////////////////
// toggleImgOver() - TOGGLES IMAGE OVER AND IMAGE OUT
// IMAGES USING REGULAR EXPRESSIONS TO REPLACE EXTENSION
// WITH ITS COUNTERPART.
//
// ARGUMENTS:
//   e - TRIGGERING EVENT
function toggleImgOver(e) {
    if(!e) e=event;

    if( e.type == "mouseover" ) {
        this.src        = this.src.replace( g_strOut_ext, g_strOver_ext );
    }
    if( e.type == "mouseout" ) {
        this.src        = this.src.replace( g_strOver_ext,g_strOut_ext );
    }
}