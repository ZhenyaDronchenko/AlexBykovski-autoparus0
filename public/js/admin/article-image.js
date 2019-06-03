$(document).ready(function (ev) {
    const EMPTY_OPTION = '<option value=""></option>';
    const PROVIDE_MODELS_LINK = "/admin/ajax/get-models-by-brand/__id__";
    const IMAGE_WIDTH = 1080;
    const IMAGE_HEIGHT = 720;

    let brandEl = $(".article-image-detail-brand-select");
    let modelEl = $(".article-image-detail-model-select");

    if(!brandEl.length || !brandEl.length){
        return false;
    }

    addBootstrapModalAfterFooter();

    let modalFooter = $('#modalAfterFooter');

    brandEl.off('change').on('change', function (ev) {
        const BRAND_ID = $(this).val();

        modelEl.html(EMPTY_OPTION);

        if(!BRAND_ID){
            return false;
        }

        $.ajax(PROVIDE_MODELS_LINK.replace("__id__", BRAND_ID), {
            method: "POST",
            success(data) {
                setModels(data);
            },
            error(data) {
                console.error('Error due request');
            },
        });
    });

    function setModels(models) {
        for(let id in models){
            let newOption = $(EMPTY_OPTION);

            newOption.val(id);
            newOption.text(models[id]);

            modelEl.append(newOption);
        }
    }

    $("body").off('change').on('change', ".file-for-cropper", function (ev) {
        modalFooter.modal();

        let modalCropperContainer = modalFooter.find(".modal-body");

        modalCropperContainer.croppie("destroy");

        resizeAndCompressImage(ev.target.files[0], function(file){
            addImagePreview(file, function (base64Image) {
                addCropper(modalCropperContainer, base64Image);
            });
        });

        saveImagePathHandle(modalCropperContainer, $(this));
    });

    function addCropper(modalCropperContainer, base64Image) {
        let viewPortWidth = window.screen.availHeight/4 * 1.5;
        let viewPortHeight = window.screen.availHeight/4;

        if(window.screen.availHeight > window.screen.availWidth){
            viewPortWidth = window.screen.availWidth/4 * 1.5;
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

    function saveImagePathHandle(modalCropperContainer, fileInput) {
        $("#save-modal-after-footer").off('click').on('click', function(event){
            modalCropperContainer.croppie('result', {
                type: 'base64',
                size: {
                    width: IMAGE_WIDTH,
                    height: IMAGE_HEIGHT
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