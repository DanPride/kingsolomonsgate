// Initialize header weather click action
jQuery("#header-weather").click(function() {location.href="http://www.rockymountainnews.com/weather/";});



// Initialize search form action selector
jQuery('a#search-web').click(function() {
    jQuery('#searchform').attr("action","http://www.rockymountainnews.com/websearch/");
    jQuery('#search-site').removeClass("active");
 	jQuery('#search-web').addClass("active");
  });

jQuery('a#search-site').click(function() {
    jQuery('#searchform').attr("action","http://search.rockymountainnews.com/sp/");
    jQuery('#search-site').addClass("active");
 	jQuery('#search-web').removeClass("active");
  });
  
// Article text size controls
var def = 13; 
function increaseTextSize(){ 
  def += 2; 
  var str = def + 'px';
  ps = document.getElementsByTagName('p'); 
  for(i=0;i<ps.length;i++){ 
    ps[i].style.fontSize = str; 
  } 
} 
 
function decreaseTextSize(){
  def -= 2; 
  var str = def + 'px';  
  ps = document.getElementsByTagName('p'); 
  for(i=0;i<ps.length;i++){ 
    ps[i].style.fontSize = str; 
  } 
}