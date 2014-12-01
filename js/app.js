// Foundation JavaScript
// Documentation can be found at: http://foundation.zurb.com/docs
$(document).foundation();

$(function() {
    if (!Modernizr.cssvhunit) {
        var windowH = $(window).height();
        $('.fb-like-box').css({'height':($(window).height())+'px'});
    }

    var ua = navigator.userAgent.toLowerCase();
    var isAndroid = ua.indexOf("android") > -1; //&& ua.indexOf("mobile");
    if(isAndroid && (typeof $.cookie('android') === 'undefined' || $.cookie('android') === null)) {
        // Show download app modal!
        $('#download-app-modal').foundation('reveal', 'open');
        $.cookie('android', 1, { expires: 7 });
    }

    $('a.custom-close-reveal-modal').click(function(){
        $('#download-app-modal').foundation('reveal', 'close');
    });

    // Bind the swipeHandler callback function to the swipe event on div.box
    //$( "div#main-comic" ).on( "swipe", swipeHandler );

    // Callback function references the event target and adds the 'swipe' class to it
    /*function swipeHandler( event ){
        //alert('test');
    }

    $('#container').imagesLoaded( function(){
        $('#container').masonry({
            itemSelector: '.item',
            isAnimated: true,
            isFitWidth: true
        });
    })*/
});