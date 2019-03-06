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
                             customUploadToServer) {
            this.cropperContentSize = cropperContentSize;
            this.previewImage = previewImage;
            this.cropperContainer = cropperContainer;
            this.dialogContentSize = dialogContentSize;
            this.input = input;
            this.fileName = fileName;
            this.uploadUrl = uploadUrl;
            this.imgPhoto = imgPhoto;
            this.customUploadToServer = customUploadToServer;
            this.jCropApi = null;
            this.isBlockedUpload = false;
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

            $(".cancel-button-cropper-dialog:visible").click(function(){
                self.cropperContainer.removeClass("modal--show");
                $("body").removeClass("modal--show");
                self.input.val('');
            });

            $(".save-button-cropper-dialog:visible").click(function(){
                //@@todo here called multiple times
                if(!self.isBlockedUpload){
                    self.processCroppedImage(this);

                    self.input.val('');

                    self.isBlockedUpload = true;
                }
            });
        };

        this.addCropper = function() {
            let JcropAPI = this.previewImage.data('Jcrop');

            if(JcropAPI) {
                JcropAPI.destroy();
            }

            getImageData(this.previewImage.attr("src"), function(data){
                const trueSize = self.getJCropTrueSize(data["width"], data["height"], (self.cropperContentSize * 4 / 3));
                const trueWidth = trueSize[0];
                const trueHeight = trueSize[1];

                $("#cropper-modal").width(Math.max(data["width"], 450));

                self.previewImage.Jcrop({
                    aspectRatio: 3 / 2,
                    maxSize: [trueWidth, trueHeight],
                    boxWidth: self.cropperContentSize,
                    boxHeight: self.cropperContentSize,
                    setSelect:   self.getJCropDefaultSelected(trueWidth, trueHeight),
                }, function () { self.jCropApi = this; });
            });
        };

        this.processCroppedImage = function(dialogInstance) {
            getImageByCoordinatesFromImage(this.previewImage.attr("src"), this.jCropApi.ui.selection.last, true, function(file){
                const formData = new FormData();

                formData.append('file', file, (new Date()).getTime() + self.fileName);

                self.sendFileToServer(dialogInstance, formData);
            });
        };

        this.getJCropTrueSize = function(width, height, maxSize) {
            if(width > height){
                const defaultScale = width / height;

                return [maxSize, maxSize/defaultScale];
            }

            const defaultScale = height / width;

            return [maxSize/defaultScale, maxSize];
        };

        this.getJCropDefaultSelected = function(width, height) {
            const heightModified = height * 1.5;
            const xWidth = width > heightModified ? heightModified : width;
            const yHeight = width > heightModified ? height : width / 1.5;

            let startX = width > heightModified ? (width - xWidth)/2 : 0;
            let startY = width > heightModified ? 0 : (height - yHeight)/2;

            return [startX, startY, xWidth, yHeight];
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
    });
})(window.autoparusApp);