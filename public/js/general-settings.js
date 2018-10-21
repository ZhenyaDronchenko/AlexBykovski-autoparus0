$(function(){
    $(".phone-registration").mask("+375  (99)  999 - 99 - 99");
    $(".phone-profile").mask("+375  (99)  999 - 99 - 99");
});

function scrollToElement(selector) {
    $([document.documentElement, document.body]).animate({
        scrollTop: $(selector).offset().top
    }, 500);
}