// Foundation JavaScript
// Documentation can be found at: http://foundation.zurb.com/docs
$(document).foundation();


$(document).ready(function () {

    var thumb = $('img#thumb');

    new AjaxUpload('imageUpload', {
        action: $('form#newHotnessForm').attr('action'),
        name: 'comic',
        onSubmit: function (file, extension) {
            $('div.preview').addClass('loading');
        },
        onComplete: function (file, response) {
            thumb.load(function () {
                $('div.preview').removeClass('loading');
                thumb.unbind();
            });
            thumb.attr('src', response);
        }
    });

});
