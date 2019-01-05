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
                             imgPhoto) {
            this.cropperContentSize = cropperContentSize;
            this.previewImage = previewImage;
            this.cropperContainer = cropperContainer;
            this.dialogContentSize = dialogContentSize;
            this.input = input;
            this.fileName = fileName;
            this.uploadUrl = uploadUrl;
            this.imgPhoto = imgPhoto;
            this.jCropApi = null;
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
            this.cropperContainer.dialog({
                modal: true,
                closeOnEscape: false,
                open: function(event, ui) {
                    $(".ui-dialog-titlebar-close", ui.dialog | ui).hide();
                    self.addCropper();
                },
                width: this.cropperContainer,
                height: this.cropperContainer,
                buttons: {
                    "Отменить": function() {
                        $( this ).dialog( "close" );

                        self.input.val('');
                    },
                    "Сохранить": function() {
                        self.processCroppedImage(this);

                        self.input.val('');
                    }
                }
            });
        };

        this.addCropper = function() {
            let JcropAPI = this.previewImage.data('Jcrop');

            if(JcropAPI) {
                JcropAPI.destroy();
            }

            getImageData(this.previewImage.attr("src"), function(data){
                const trueSize = self.getJCropTrueSize(data["width"], data["height"], self.cropperContentSize);
                const trueWidth = trueSize[0];
                const trueHeight = trueSize[1];
                self.cropperContainer.width(trueWidth);
                self.cropperContainer.height(trueHeight);

                self.previewImage.Jcrop({
                    aspectRatio: 3 / 2,
                    maxSize: [self.cropperContentSize, self.cropperContentSize],
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
            const baseSide = width > height ? height : width;
            const selectedH = baseSide / 9;
            const selectedW = baseSide / 6;

            const x = width/3 - selectedW/3;
            const y = height/3 - selectedH/3;
            const x1 = x + selectedW;
            const y1 = y + selectedH;

            return [x, y, x1, y1];
        };

        this.sendFileToServer = function(dialogInstance, formData) {
            getLocation(function(position){
                let coordinates = !position || !position.coords ? null : JSON.stringify({
                    "latitude" : position.coords.latitude,
                    "longitude" : position.coords.longitude,
                });

                formData.append('coordinates', coordinates);

                $.ajax(self.uploadUrl, {
                    method: "POST",
                    data: formData,
                    processData: false,
                    contentType: false,
                    success(data) {
                        if(data.success) {
                            self.imgPhoto.attr("src", data.path);
                        }

                        $( dialogInstance ).dialog( "close" );
                    },
                    error(data) {
                        console.error('Upload error');
                    },
                });
            });
        };
    });
})(window.autoparusApp);