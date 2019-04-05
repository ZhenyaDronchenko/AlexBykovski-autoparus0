(function(autoparusApp) {
    'use strict';

    autoparusApp.directive("galleryUpload",["$compile", "$rootScope", "ImageUploadService",
        function($compile, $rootScope, ImageUploadService){
        return{
            restrict: 'A',
            scope: true,
            link: function(scope, element, attrs)
            {
                let id = attrs.galleryId;
                let uploadUrl = attrs.actionUrl;
                let removePath = attrs.removeLink;
                let input = $(attrs.inputSelector);
                let dialogContentSize = window.screen.availWidth > window.screen.availHeight ? window.screen.availHeight : window.screen.availWidth;
                let cropperContentSize = dialogContentSize * 0.6;
                let removePopupSelector = "#remove-gallery-photo-confirm";


                function editGalleryPhoto(){
                    $(attrs.inputSelector).trigger("click");
                }

                function removeGalleryPhoto(){
                    $(removePopupSelector).attr("data-photo-id", id).addClass("modal--show");
                }

                $("#remove-gallery-photo").off("click").click(function () {
                    $.ajax(removePath.replace("__rid__", $(removePopupSelector).attr("data-photo-id")), {
                        success(data) {
                            if(data.success) {
                                $("#gallery-photo-" + $(removePopupSelector).attr("data-photo-id")).remove();
                            }

                            $(removePopupSelector).removeClass("modal--show")
                        },
                        error(data) {
                            console.error('Upload error');

                            $(removePopupSelector).removeClass("modal--show")
                        },
                    });
                });

                $("#cancel-remove-gallery-photo").off("click").click(function () {
                    $(removePopupSelector).removeClass("modal--show");
                });

                $("#edit-from-remove-popup").off("click").click(function () {
                    $(removePopupSelector).removeClass("modal--show");
                    $("#gallery-input-" + $(removePopupSelector).attr("data-photo-id")).trigger("click");
                });

                $(document).on("change", attrs.inputSelector, function (e) {
                    if(id){
                        $("#left-without-comment-gallery").show();
                        $("#add-comment-later-gallery").hide();
                        $("#without-comment-gallery").hide();
                        $("#change-image-button-gallery").attr("data-photo-id", id).show();
                    }
                    else{
                        $("#left-without-comment-gallery").hide();
                        $("#add-comment-later-gallery").show();
                        $("#without-comment-gallery").show();
                        $("#change-image-button-gallery").hide();
                    }

                    let cropperContainer = $(attrs.cropperContainer);
                    let previewImage = $("#image-preview-container-gallery img");
                    let fileName = e.target.files[0].name;
                    const description = $(element).find(".gallery-photo-description").html() ?
                        $(element).find(".gallery-photo-description").html() : "";

                    $("#gallery-photo-description").val(description);

                    ImageUploadService.init(cropperContentSize, previewImage, cropperContainer, dialogContentSize,
                        input, fileName, uploadUrl, null,
                        function (formData) {
                            formData.append('description', $("#gallery-photo-description").val());

                            $.ajax(uploadUrl, {
                                method: "POST",
                                data: formData,
                                processData: false,
                                contentType: false,
                                success(data) {
                                    if(data.success) {
                                        setGalleryPhoto(data.gallery);
                                    }

                                    cropperContainer.removeClass("modal--show");
                                    $("#dialog-cropper-container-gallery2").removeClass("modal--show");
                                    $("body").removeClass("modal--show");
                                    $(e.target).val('');
                                },
                                error(data) {
                                    console.error('Upload error');
                                },
                            });
                        });

                    ImageUploadService.processUploadImage(e.target.files[0]);
                });

                function setGalleryPhoto(gallery){
                    if(!id){
                        return addNewGalleryPhoto(gallery);
                    }

                    updateExistGalleryPhoto(gallery);
                }

                function addNewGalleryPhoto(gallery) {
                    let templateEl = $("#gallery-photo-template");

                    if(!templateEl.length){
                       return false;
                    }

                    let template = templateEl.html();
                    template = template.replace(/__id__/g, gallery.id);
                    let el = $compile(template)( scope );

                    let newGalleryPhoto = $(el);

                    newGalleryPhoto.find(".gallery-photo-image").attr("src", gallery.path);
                    newGalleryPhoto.find(".gallery-photo-date").html(gallery.date);
                    newGalleryPhoto.find(".gallery-photo-time").html(gallery.time);
                    newGalleryPhoto.find(".gallery-photo-address").html(gallery.address);
                    newGalleryPhoto.find(".gallery-photo-description").html(gallery.description);

                    $("#gallery-photos-container").prepend(newGalleryPhoto);
                }

                function updateExistGalleryPhoto(gallery) {
                    let galleryPhoto = $("#gallery-photo-" + id);

                    galleryPhoto.find(".gallery-photo-image").attr("src", gallery.path);
                    galleryPhoto.find(".gallery-photo-date").html(gallery.date);
                    galleryPhoto.find(".gallery-photo-time").html(gallery.time);
                    galleryPhoto.find(".gallery-photo-address").html(gallery.address);
                    galleryPhoto.find(".gallery-photo-description").html(gallery.description);
                }

                if(!id){
                    $rootScope.$on('added-new-user-gallery-photo', function(event, args) {
                        addNewGalleryPhoto(args.galleryPhoto);
                    });
                }

                scope.removeGalleryPhoto = removeGalleryPhoto;
                scope.editGalleryPhoto = editGalleryPhoto;
            }
        };
    }]);

})(window.autoparusApp);