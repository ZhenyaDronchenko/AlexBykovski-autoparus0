const BASE_IMAGE_WIDTH = 1080;
const BASE_IMAGE_HEIGHT = 720;

$(function(){
    if(typeof $("body").mask === "function") {
        $(".phone-registration").mask("+375  (99)  999 - 99 - 99");
        $(".phone-profile").mask("+375  (99)  999 - 99 - 99");
        $(".phone-mask").mask("+375  (99)  999 - 99 - 99");
    }

    $(document).on("click", ".open-popup-button", function (ev) {
        if($(this).attr("id") && $(this).attr("id").indexOf("initiator-open-") > -1){
            $("#" + $(this).attr("id").replace("initiator-open-", "")).show();
            $("body").addClass("modal--show");
        }
        else{
            $("#popup" + $(this).attr("data-popup-id")).show();
            $("body").addClass("modal--show");
        }
    });

    $(document).on("click", ".close-popup-button", function (ev) {
        if($(this).attr("id") && $(this).attr("id").indexOf("initiator-close-") > -1){
            $("#" + $(this).attr("id").replace("initiator-close-", "")).hide();
            $("body").removeClass("modal--show");
        }
        else{
            $("#popup" + $(this).attr("data-popup-id")).hide();
            $("body").removeClass("modal--show");
        }
    });
});

function scrollToElement(selector) {
    $([document.documentElement, document.body]).animate({
        scrollTop: $(selector).offset().top
    }, 500);
}

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

function getImageByCoordinatesFromImage(blob, isBlob, callback) {
    if(isBlob){
        return callback(blob);
    }

    const file = new File([blob], "preview_image" + (new Date()).getTime(), {
        type: 'image/jpeg',
        lastModified: Date.now()
    });

    callback(file);
}

function convertBase64ToImage(image64, callback) {
    const img = new Image();
    img.src = image64;

    img.onload = () => {
        callback(img);
    };
}

function resizeAndCompressImage(file, callback) {
    const fileName = file.name;
    const reader = new FileReader();

    reader.readAsDataURL(file);

    reader.onload = event => {
        const img = new Image();
        img.src = event.target.result;

        img.onload = () => {
            const SCALE = img.width / img.height;
            const elem = document.createElement('canvas');

            if(img.width > img.height){
                elem.width = img.width > BASE_IMAGE_WIDTH ? BASE_IMAGE_WIDTH : img.width;
                elem.height = elem.width / SCALE;
            }
            else{
                elem.height = img.height > BASE_IMAGE_HEIGHT ? BASE_IMAGE_HEIGHT : img.height;
                elem.width = elem.height * SCALE;
            }

            const ctx = elem.getContext('2d');

            // img.width and img.height will give the original dimensions
            ctx.drawImage(img, 0, 0, elem.width, elem.height);

            ctx.canvas.toBlob((blob) => {
                const file = new File([blob], fileName, {
                    type: 'image/jpeg',
                    lastModified: Date.now()
                });

                callback(file);

            }, 'image/jpeg', 1);
        };
        reader.onerror = error => console.log(error);
    };
}

function getImageScaledSizes(width, height, maxSize) {
    if(width > height){
        const defaultScale = width / height;

        return [maxSize, maxSize/defaultScale];
    }

    const defaultScale = height / width;

    return [maxSize/defaultScale, maxSize];
}

function getLocation(callback) {
    if(location.protocol !== "https:"){
        return callback(null);
    }

    if (navigator.geolocation) {
        navigator.geolocation.getCurrentPosition(
            callback,
            function (error) {
                if (error.code === error.PERMISSION_DENIED) {
                    console.log("You close access to your geolocation");
                }
                callback(null);
            });
    } else {
        callback(null);
    }
}

function detectmob() {
    return window.innerWidth <= 800 && window.innerHeight <= 600;
}

function addBootstrapModalAfterFooter() {
    if($("#modalAfterFooter").length){
        return false;
    }

    let modalHtml =
        '<div id="modalAfterFooter" class="modal" tabindex="-1" role="dialog">' +
            '<div class="modal-dialog" role="document">' +
                '<div class="modal-content">' +
                    '<div class="modal-header">' +
                    '</div>' +
                    '<div class="modal-body">' +
                    '</div>' +
                    '<div class="modal-footer">' +
                        '<button id="save-modal-after-footer" type="button" class="btn btn-primary">Сохранить</button>' +
                        '<button id="close-modal-after-footer" type="button" class="btn btn-secondary" data-dismiss="modal">Закрыть</button>' +
                    '</div>' +
                '</div>' +
            '</div>' +
        '</div>';

    $("body").append($(modalHtml));
}