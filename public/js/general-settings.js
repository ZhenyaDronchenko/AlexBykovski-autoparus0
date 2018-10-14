$(function(){
    $(".phone-registration").mask("+375  (99)  999 - 99 - 99");
    $(".phone-profile").mask("+375  (99)  999 - 99 - 99");
});


// $(document).ready(function(){
//     $(".set-backgroud-url").each(function(index, item){
//         let url = $(item).attr("data-url");
//         let background = $(item).css("background");
//         background = background.substring(0, background.indexOf(" / "));
//         background = background.replace("none ", "").replace("scroll ", "");
//
//         $(item).css("background", "url(" + url + ")" + " " + background);
//     });
// });

function dataURLtoBlob(dataurl) {
    let arr = dataurl.split(','), mime = arr[0].match(/:(.*?);/)[1],
        bstr = atob(arr[1]), n = bstr.length, u8arr = new Uint8Array(n);
    while(n--){
        u8arr[n] = bstr.charCodeAt(n);
    }
    return new Blob([u8arr], {type:mime});
}

function addImagePreview(file, callback) {
    if (file) {
        let reader = new FileReader();

        reader.onload = function(e) {
            callback(e.target.result);
        };

        reader.readAsDataURL(file);
    }
}