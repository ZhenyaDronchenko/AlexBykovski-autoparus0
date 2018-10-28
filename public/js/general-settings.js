$(function(){
    $(".phone-registration").mask("+375  (99)  999 - 99 - 99");
    $(".phone-profile").mask("+375  (99)  999 - 99 - 99");
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

function getImageByCoordinatesFromImage(image64, coordinates, isBlob, callback) {
    const canvas = document.createElement('canvas');

    const img = new Image();
    img.src = image64;

    img.onload = () => {
        const sourceX = coordinates["x"];
        const sourceY = coordinates["y"];
        const width = coordinates["x2"] - sourceX;
        const height = coordinates["y2"] - sourceY;

        canvas.width = width;
        canvas.height = height;
        const context = canvas.getContext('2d');

        context.drawImage(img, sourceX, sourceY, width, height, 0, 0, width, height);

        context.canvas.toBlob((blob) => {
            if(isBlob){
                return callback(blob);
            }

            const file = new File([blob], "preview_image" + (new Date()).getTime(), {
                type: 'image/jpeg',
                lastModified: Date.now()
            });

            callback(file);

        }, 'image/jpeg', 1);
    };
}

function compress(file, sizes, callback) {
    const fileName = file.name;
    const reader = new FileReader();
    reader.readAsDataURL(file);
    reader.onload = event => {
        const img = new Image();
        img.src = event.target.result;

        img.onload = () => {
            let width = null;
            let height = null;
            if(sizes){
                if(sizes.length === 1){
                    sizes = getImageScaledSizes(img.width, img.height, sizes[0]);
                }

                width = sizes[0];
                height = sizes[1];

            }
            else {
                width = 900 > img.width ? img.width : 900;
                height = 600 > img.height ? img.height : 600;
            }

            const elem = document.createElement('canvas');
            elem.width = width;
            elem.height = height;
            const ctx = elem.getContext('2d');
            // img.width and img.height will give the original dimensions
            ctx.drawImage(img, 0, 0, width, height);
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

function getImageData(imageData, callback) {
    let img = new Image();

    img.onload = function(){
        callback({
            "width" : img.width,
            "height" : img.height,
        });
    };

    img.src = imageData;
}

function getImageScaledSizes(width, height, maxSize) {
    if(width > height){
        const defaultScale = width / height;

        return [maxSize, maxSize/defaultScale];
    }

    const defaultScale = height / width;

    return [maxSize/defaultScale, maxSize];
}