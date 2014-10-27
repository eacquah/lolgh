// Foundation JavaScript
// Documentation can be found at: http://foundation.zurb.com/docs
$(document).foundation();

$(function() {
    if (!Modernizr.cssvhunit) {
        var windowH = $(window).height();
        $('.fb-like-box').css({'height':($(window).height())+'px'});
    }

    // Bind the swipeHandler callback function to the swipe event on div.box
    $( "div#main-comic" ).on( "swipe", swipeHandler );

    // Callback function references the event target and adds the 'swipe' class to it
    function swipeHandler( event ){
        alert('test');
    }
});