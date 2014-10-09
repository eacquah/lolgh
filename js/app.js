// Foundation JavaScript
// Documentation can be found at: http://foundation.zurb.com/docs
$(document).foundation();

$(function() {
    if (!Modernizr.cssvhunit) {
        var windowH = $(window).height();
        $('.fb-like-box').css({'height':($(window).height())+'px'});
    }
});