(function(autoparusApp) {
    'use strict';

    autoparusApp.service('ImageUploadService', function(){
        let self = this;

        this.init = function(cropperContentSize,
                             previewImage,
                             cropperContainer,
                             dialogContentSize,
                             input,
                             fileName,
                             uploadUrl,
                             imgPhoto,
                             customUploadToServer,
                             imageSizes) {
            this.cropperContentSize = cropperContentSize;
            this.previewImage = previewImage;
            this.cropperContainer = cropperContainer;
            this.dialogContentSize = dialogContentSize;
            this.input = input;
            this.fileName = fileName;
            this.uploadUrl = uploadUrl;
            this.imgPhoto = imgPhoto;
            this.customUploadToServer = customUploadToServer;
            this.cropper = null;
            this.isBlockedUpload = false;
            this.imageSizes = imageSizes;
        };

        this.processUploadImage = function(initFile, callback) {
            let sizes = this.cropperContentSize ? [this.cropperContentSize] : null;

            compress(initFile, sizes, function(file){
                if(callback) {
                    return callback(file);
                }

                self.workAfterCompress(file);

            });
        };

        this.workAfterCompress = function(file) {
            addImagePreview(file, function(data){
                self.previewImage.attr("src", data);

                self.addDialog();
            });
        };

        this.addDialog = function() {
            $("body").addClass("modal--show");
            this.cropperContainer.addClass("modal--show");

            self.addCropper();

            $(".move-to-popup").click(function(){
                $(this).parents(".overlay").removeClass("modal--show");
                $(".overlay" + $(this).attr("data-popup")).addClass("modal--show");

                updateSaveCloseButtons();
            });

            updateSaveCloseButtons();
        };

        this.addCropper = function() {
            let element = $('#image-preview-container-gallery');
            element.croppie('destroy');

            let image_crop = element.croppie({
                enableExif: true,
                viewport: {
                    width: 300,
                    height: 200,
                    type: 'square'
                },
                boundary: {
                    width: element.width(),
                    height: element.height(),
                },
                showZoomer: false,
            });

            self.cropper = image_crop;

            image_crop.croppie('bind', {
                url: self.previewImage.attr("src")
            });
        };

        this.processCroppedImage = function(dialogInstance) {
            $("#dialog-cropper-container-gallery").css({
                "display" : "block",
                "opacity" : "0",
            });

            $('#image-preview-container-gallery').croppie('result', {
                type: 'blob',
                size: 'original'
            }).then(function (response) {
                $("#dialog-cropper-container-gallery").removeAttr("style");

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
                $('#image-preview-container-gallery').croppie('destroy');
            });
        };

        function updateSaveCloseButtons() {
            $(".cancel-button-cropper-dialog:visible").off().on("click", function(){
                self.cropperContainer.removeClass("modal--show");
                $("body").removeClass("modal--show");
                self.input.val('');
                $('#image-preview-container-gallery').croppie('destroy');
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
                self.cropperContainer.removeClass("modal--show");
                $("body").removeClass("modal--show");
                self.input.val('');
                $("#gallery-input-" + $(this).attr("data-photo-id")).trigger("click");
            });
        }
    });
})(window.autoparusApp);