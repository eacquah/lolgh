// Foundation JavaScript
// Documentation can be found at: http://foundation.zurb.com/docs
$(document).foundation();

$(function () {
    if (!Modernizr.cssvhunit) {
        var windowH = $(window).height();
        $('.fb-like-box').css({'height': ($(window).height()) + 'px'});
    }

    var ua = navigator.userAgent.toLowerCase();
    var isAndroid = ua.indexOf("android") > -1; //&& ua.indexOf("mobile");
    if (isAndroid && (typeof $.cookie('android') === 'undefined' || $.cookie('android') === null)) {
        // Show download app modal!
        $('#download-app-modal').foundation('reveal', 'open');
        $.cookie('android', 1, {expires: 7});
    }

    $('a.custom-close-reveal-modal').click(function () {
        $('#download-app-modal').foundation('reveal', 'close');
    });

    // Setup form validation on the #register-form element
    $("#contact-form").validate({

        // Specify the validation rules
        rules: {
            name: "required",
            email: {
                required: true,
                email: true
            },
            msg: {
                required: true
            }
        },

        // Specify the validation error messages
        messages: {
            name: "Please enter your name",
            email: "Please enter a valid email address",
            msg: "Please enter a message"
        },

        submitHandler: function(form) {
            form.submit();
        }
    });
});