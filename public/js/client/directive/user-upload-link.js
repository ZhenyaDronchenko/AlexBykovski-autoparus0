(function(autoparusApp) {
    'use strict';

    autoparusApp.directive("userUploadLink",["$rootScope", "ImageUploadService", function($rootScope, ImageUploadService){
        return{
            restrict: 'A',
            link: function(scope, element, attrs)
            {
                let link = $(attrs.linkSelector);
                let input = $(attrs.inputSelector);
                let imgPhoto = $(attrs.imgSelector);
                let uploadUrl = attrs.actionUrl;
                let cropperContainer = $(attrs.cropperContainer);

                link.click(function(e){
                    input.trigger("click");
                });

                let dialogContentSize = window.innerWidth > window.innerHeight ? window.innerHeight : window.innerWidth;
                let cropperContentSize = dialogContentSize * 0.75;

                input.change(function(e){
                    let previewImage = $("#image-preview-container img");
                    let fileName = e.target.files[0].name;

                    ImageUploadService.init(cropperContentSize, previewImage, cropperContainer, dialogContentSize,
                        input, fileName, uploadUrl, imgPhoto, function(formData){
                            $.ajax(uploadUrl, {
                                method: "POST",
                                data: formData,
                                processData: false,
                                contentType: false,
                                success(data) {
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
                                    console.error('Upload error');
                                },
                            });
                        });

                    ImageUploadService.processUploadImage(e.target.files[0]);
                });
            }
        };
    }]);

})(window.autoparusApp);