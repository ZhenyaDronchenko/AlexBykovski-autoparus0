(function(autoparusApp) {
    'use strict';

    autoparusApp.service('ImageUploadService', function(){
        let self = this;

        this.init = function(cropperContentSize,
                             previewImage,
                             cropperModal,
                             dialogContentSize,
                             input,
                             fileName,
                             uploadUrl,
                             imgPhoto,
                             customUploadToServer,
                             imageSizes) {
            this.cropperContentSize = cropperContentSize;
            this.previewImage = previewImage;
            this.cropperModal = cropperModal;
            this.dialogContentSize = dialogContentSize;
            this.input = input;
            this.fileName = fileName;
            this.uploadUrl = uploadUrl;
            this.imgPhoto = imgPhoto;
            this.customUploadToServer = customUploadToServer;
            this.isBlockedUpload = false;
            this.imageSizes = imageSizes;
        };

        this.processUploadImage = function(initFile, callback, sizes) {
            //self.workAfterCompress(initFile);

            resizeAndCompressImage(initFile, function(file){
                if(callback) {
                    return callback(file);
                }

                self.workAfterCompress(file);

            }, sizes);
        };

        this.workAfterCompress = function(file) {
            addImagePreview(file, function(data){
                self.previewImage.attr("src", data);

                self.addDialog();
            });
        };

        this.addDialog = function() {
            $("body").addClass("modal--show");
            this.cropperModal.addClass("modal--show");

            self.addCropper();

            $(".move-to-popup").click(function(){
                $(this).parents(".overlay").removeClass("modal--show");
                $(".overlay" + $(this).attr("data-popup")).addClass("modal--show");

                updateSaveCloseButtons();
            });

            updateSaveCloseButtons();
        };

        this.addCropper = function() {
            let galleryContainer = this.cropperModal.find(".cropper-container");
            const SCALE = this.imageSizes && this.imageSizes.length === 2 ? this.imageSizes[0]/this.imageSizes[1] : 1.5;
            const containerHeight = galleryContainer.height();
            const containerWidth = galleryContainer.width();

            destroyCroppie();

            let viewPortWidth = containerHeight * SCALE;
            let viewPortHeight = containerHeight;

            if(viewPortWidth > containerWidth){
                viewPortWidth = containerWidth;
                viewPortHeight = viewPortWidth / SCALE;
            }

            //https://foliotek.github.io/Croppie/
            let image_crop = this.cropperModal.find(".cropper-container").croppie({
                enableExif: true,
                viewport: {
                    width: viewPortWidth,
                    height: viewPortHeight,
                    type: 'square'
                },
                boundary: {
                    width: containerWidth,
                    height: containerHeight,
                },
                mouseWheelZoom: true,
                showZoomer: false,
                customClass: "croppie-container-class"
            });

            image_crop.croppie('bind', {url: self.previewImage.attr("src")}).then(function () {
                image_crop.croppie('setZoom', 0)
            });
        };

        this.processCroppedImage = function(dialogInstance) {
            const WIDTH = this.imageSizes && this.imageSizes.length === 2 ? this.imageSizes[0] : 1080;
            const HEIGHT = this.imageSizes && this.imageSizes.length === 2 ? this.imageSizes[1] : 720;

            if(!this.cropperModal.is(":visible")){
                this.cropperModal.css({
                    "display" : "block",
                    "opacity" : "0",
                });
            }

            this.cropperModal.find(".cropper-container").croppie('result', {
                type: 'blob',
                size: {
                    width: WIDTH,
                    height: HEIGHT
                }
            }).then(function (response) {
                self.cropperModal.removeAttr("style");

                const formData = new FormData();

                formData.append('file', response, (new Date()).getTime() + self.fileName);

                self.sendFileToServer(dialogInstance, formData);
            });
        };

        this.sendFileToServer = function(dialogInstance, formData) {
            getLocation(function(position){
                let coordinates = !position || !position.coords ? null : JSON.stringify({
                    "latitude" : position.coords.latitude,
                    "longitude" : position.coords.longitude,
                });

                formData.append('coordinates', coordinates);

                self.customUploadToServer(formData);
            });
        };

        function updateSaveCloseButtons() {
            $(".cancel-button-cropper-dialog:visible").off().on("click", function(){
                self.cropperModal.removeClass("modal--show");
                $("body").removeClass("modal--show");
                self.input.val('');
                destroyCroppie();
            });

            $(".save-button-cropper-dialog:visible").off().on("click", function(){
                //@@todo here called multiple times
                if(!self.isBlockedUpload){
                    self.processCroppedImage(this);

                    self.input.val('');

                    self.isBlockedUpload = true;
                }
            });

            $(".change-image-button-cropper-dialog:visible").off().on("click", function(){
                self.cropperModal.removeClass("modal--show");
                $("body").removeClass("modal--show");
                self.input.val('');
                $("#gallery-input-" + $(this).attr("data-photo-id")).trigger("click");
            });
        }

        function destroyCroppie() {
            let element = self.cropperModal.find(".cropper-container");

            element.croppie("destroy");
            element.removeClass("croppie-container-class");
        }
    });
})(window.autoparusApp);