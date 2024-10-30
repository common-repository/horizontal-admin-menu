    jQuery(document).ready(function() {
        //Fix ie7 z-index issues
        //Thanks to http://richa.avasthi.name/blogs/tepumpkin/2008/01/11/ie7-lessons-learned/
        jQuery("div.wp-submenu").parents().each(function() {
            var p = jQuery(this);
            var pos = p.css("position");
            if(pos == "relative" ||
                pos == "absolute" ||
                pos == "fixed") {
                p.hover(function() {
                    jQuery(this).addClass("on-top");
                    },
                    function() {
                        jQuery(this).removeClass("on-top");
                    }
                );
            }
        });
    });
