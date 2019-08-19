(function(autoparusApp) {
    'use strict';

    autoparusApp.directive("userUploadLink",["$rootScope", "ImageUploadService", function($rootScope, ImageUploadService){
        return{
            restrict: 'A',
            scope: true,
            link: function(scope, element, attrs)
            {
                let link = $(attrs.linkSelector);
                let input = $(attrs.inputSelector);
                let imgPhoto = $(attrs.imgSelector);
                let uploadUrl = attrs.actionUrl;
                let cropperContainer = $(attrs.cropperContainer);
                let imageWidth = attrs.imageWidth;
                let imageHeight = attrs.imageHeight;
                let imageSizes = !imageWidth || !imageHeight ? null : [imageWidth, imageHeight];

                const PREVIEW_IMAGE_SELECTOR = ".preloader-image";

                link.click(function(e){
                    input.trigger("click");
                });

                let dialogContentSize = window.screen.availWidth > window.screen.availHeight ? window.screen.availHeight : window.screen.availWidth;
                let cropperContentSize = dialogContentSize * 0.6;

                input.change(function(e){
                    let previewImage = $("#image-preview-container img");
                    let fileName = e.target.files[0].name;

                    ImageUploadService.init(cropperContentSize, previewImage, cropperContainer, dialogContentSize,
                        input, fileName, uploadUrl, imgPhoto, function(formData){
                            cropperContainer.parent().find(PREVIEW_IMAGE_SELECTOR).show();

                            $.ajax(uploadUrl, {
                                method: "POST",
                                data: formData,
                                processData: false,
                                contentType: false,
                                success(data) {
                                    cropperContainer.parent().find(PREVIEW_IMAGE_SELECTOR).hide();

                                    if (data.success) {
                                        imgPhoto.attr("src", data.path);

                                        if(data.galleryPhoto){
                                            $rootScope.$broadcast("added-new-user-gallery-photo", {
                                                galleryPhoto: data.galleryPhoto
                                            });
                                        }
                                    }

                                    cropperContainer.removeClass("modal--show");
                                    $("body").removeClass("modal--show");
                                    input.val('');
                                },
                                error(data) {
                                    cropperContainer.parent().find(PREVIEW_IMAGE_SELECTOR).hide();
                                    console.error('Upload error');
                                },
                            });
                        }, imageSizes);

                    ImageUploadService.processUploadImage(e.target.files[0]);
                });
            }
        };
    }]);

})(window.autoparusApp);