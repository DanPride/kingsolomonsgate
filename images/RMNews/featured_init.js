function mycarousel_initCallback(carousel)
{
    // Disable autoscrolling if the user clicks the prev or next button.
    carousel.buttonNext.bind('click', function() {
        carousel.startAuto(0);
    });

    carousel.buttonPrev.bind('click', function() {
        carousel.startAuto(0);
    });

    // Pause autoscrolling if the user moves with the cursor over the clip.
    carousel.clip.hover(function() {
        carousel.stopAuto();
    }, function() {
        carousel.startAuto();
    });
    
    
    jQuery("#page-1").bind('click', function() {
    	carousel.scroll(1);
        carousel.startAuto(0);
    	return false;
    });
    jQuery("#page-2").bind('click', function() {
    	carousel.scroll(4);
        carousel.startAuto(0);
    	return false;
    });
    jQuery("#page-3").bind('click', function() {
    	carousel.scroll(7);
        carousel.startAuto(0);
    	return false;
    });
};

function itemVisible (carousel, item, idx, state) {
		if(idx==1){
			jQuery(".page").css("background-position","0 0");
			}
		else if(idx==4){
			jQuery(".page").css("background-position","0 -10px");
			}
		else if(idx==7){
			jQuery(".page").css("background-position","0 -20px");
			};
};
