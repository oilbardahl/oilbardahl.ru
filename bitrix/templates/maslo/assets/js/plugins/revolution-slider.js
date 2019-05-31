var RevolutionSlider = function () {

    return {
        
        //Revolution Slider - Full Width
        initRSfullWidth: function () {
		    var revapi;
	        jQuery(document).ready(function() {
	            revapi = jQuery('.tp-banner').revolution(
	            {
	                delay:9000,
	                startwidth:1170,
	                startheight:500,
	                hideThumbs:10,
					 navigationType:"bullet",
					 navigationArrows:"solo",
					 navigationStyle:"round"
	            });
	        });
        },

        //Revolution Slider - Full Screen Offset Container
        initRSfullScreenOffset: function () {
		    var revapi;
	        jQuery(document).ready(function() {
	           revapi = jQuery('.tp-banner').revolution(
	            {
	                delay:15000,
	                startwidth:1170,
	                startheight:500,
	                hideThumbs:10,
	                fullWidth:"off",
	                fullScreen:"on",
	                hideCaptionAtLimit: "",
	                dottedOverlay:"twoxtwo",
	                navigationStyle:"round",
	                fullScreenOffsetContainer: ".header"
	            });
	        });
        }        

    };
}();        