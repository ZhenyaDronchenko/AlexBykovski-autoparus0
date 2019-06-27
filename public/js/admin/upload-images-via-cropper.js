$(document).ready(function (ev) {
    addBootstrapModalAfterFooter();
    let modalFooter = $('#modalAfterFooter');

    $("body").off('change').on('change', ".file-for-cropper", function (ev) {
        modalFooter.modal();

        let modalCropperContainer = modalFooter.find(".modal-body");
        let width = $(this).parents(".article-images-container").attr("data-image-width");
        let height = $(this).parents(".article-images-container").attr("data-image-height");

        modalCropperContainer.croppie("destroy");

        resizeAndCompressImage(ev.target.files[0], function(file){
            addImagePreview(file, function (base64Image) {
                addCropper(modalCropperContainer, base64Image, width/height);
            });
        });

        saveImagePathHandle(modalCropperContainer, $(this), width, height);
    });

    function addCropper(modalCropperContainer, base64Image, scale) {
        let viewPortWidth = window.screen.availHeight/4 * scale;
        let viewPortHeight = window.screen.availHeight/4;

        if(window.screen.availHeight > window.screen.availWidth){
            viewPortWidth = window.screen.availWidth/4 * scale;
            viewPortHeight = window.screen.availWidth/4;
        }

        let image_crop = modalCropperContainer.croppie({
            enableExif: true,
            viewport: {
                width: viewPortWidth,
                height: viewPortHeight,
                type: 'square'
            },
            boundary: {
                width: window.screen.availWidth/3,
                height: window.screen.availHeight/3,
            },
            mouseWheelZoom: true,
            showZoomer: true,
        });

        image_crop.croppie('bind', {
            url: base64Image
        });
    }

    function saveImagePathHandle(modalCropperContainer, fileInput, width, height) {
        $("#save-modal-after-footer").off('click').on('click', function(event){
            modalCropperContainer.croppie('result', {
                type: 'base64',
                size: {
                    width: width,
                    height: height
                }
            }).then(function (response) {
                let parent = fileInput.parents(".sonata-collection-row");

                if(!parent.length){
                    parent = fileInput.parents(".single-image-for-cropper-container");
                }

                parent.find(".file-path-for-cropper").val(response);
                modalFooter.modal('hide');
            });
        });
    }
});