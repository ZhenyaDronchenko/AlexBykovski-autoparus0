$(function(){
    $(".phone-registration").mask("+375  (99)  999 - 99 - 99");
});


$(document).ready(function(){
    $(".set-backgroud-url").each(function(index, item){
        let url = $(item).attr("data-url");
        let background = $(item).css("background");
        background = background.substring(0, background.indexOf(" / "));
        background = background.replace("none ", "").replace("scroll ", "");

        $(item).css("background", "url(" + url + ")" + " " + background);
    });
});