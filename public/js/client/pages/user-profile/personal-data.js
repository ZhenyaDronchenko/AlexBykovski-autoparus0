'use strict';

$('document').ready(function () {
    let businessProfileButton = $('.profil-business');
    let baseLabel = businessProfileButton.html();

    businessProfileButton.click(function () {
        if ($(this).html() === baseLabel) {
            $(this).html('Свернуть бизнес профиль');
            $('#form-business-profile-container').show();
        } else {
            $(this).html(baseLabel);
            $('#form-business-profile-container').hide();
        }
    });

    $('.title-wrapper a.help-link-business-profile').click(function(){
        $('.podskazka-m').show();

        $('.close-m').click(function(){
            $('.podskazka-m').hide();
        })
    })
});


