(function(autoparusApp) {
    'use strict';

    autoparusApp.directive("userUploadLink",["ImageUploadService", function(ImageUploadService){
        return{
            restrict: 'A',
            link: function(scope, element, attrs)
            {
                let link = $(attrs.linkSelector);
                let input = $(attrs.inputSelector);
                let imgPhoto = $(attrs.imgSelector);
                let uploadUrl = attrs.actionUrl;
                let cropperContainer = $("#dialog-cropper-container");

                link.click(function(e){
                    input.trigger("click");
                });

                let dialogContentSize = window.innerWidth > window.innerHeight ? window.innerHeight : window.innerWidth;
                let cropperContentSize = dialogContentSize;

                cropperContainer.width(dialogContentSize);
                cropperContainer.height(dialogContentSize);

                input.change(function(e){
                    let previewImage = $("#image-preview-container img");
                    let fileName = e.target.files[0].name;

                    ImageUploadService.init(cropperContentSize, previewImage, cropperContainer, dialogContentSize,
                        input, fileName, uploadUrl, imgPhoto);

                    ImageUploadService.processUploadImage(e.target.files[0]);
                });
            }
        };
    }]);

})(window.autoparusApp);