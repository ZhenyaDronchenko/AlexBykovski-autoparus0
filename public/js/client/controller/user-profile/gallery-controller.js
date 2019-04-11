(function(autoparusApp) {
    'use strict';

    autoparusApp.controller('GalleryCtrl', ["$scope", "$http", "$compile", "$rootScope", "ImageUploadService",
        function($scope, $http, $compile, $rootScope, ImageUploadService) {
        const REMOVE_LINK = Routing.generate('user_office_remove_gallery_photo_ajax', {"id" : "__rid__"});
        const ADD_LINK = Routing.generate('user_office_add_gallery_photo_ajax');
        const EDIT_LINK = Routing.generate('user_office_edit_gallery_photo_ajax', {"id" : "__id__"});
        const ALL_POSTS_LINK = Routing.generate('user_office_get_all_gallery_ajax');
        const REMOVE_GALLERY_CAR_LINK = Routing.generate('remove_gallery_car_ajax', {"id" : "__id__"});
        const PREVIEW_IMAGE = $("#image-preview-container-gallery img");

        let self = this;
        let closeRemoveConfirm = $("#close-popup-5");
        let dialogContentSize = window.screen.availWidth > window.screen.availHeight ? window.screen.availHeight : window.screen.availWidth;
        let cropperContentSize = dialogContentSize * 0.6;
        let cropperDialog = null;

        this.activePost = null;
        this.posts = {};

        function init(cropperDialogS, isUploadPosts) {
            cropperDialog = cropperDialogS;

            if (isUploadPosts){
                getPosts();
            }
        }
        
        function getNewPost() {
            return {
                "address": "",
                "date": "",
                "description": "",
                "id": null,
                "path": "",
                "time": ""
            };
        }

        function editPost(eventUpload) {
            let cropperContainer = $(cropperDialog);
            let fileName = eventUpload ? eventUpload.target.files[0].name : self.activePost.path.replace(/^.*[\\\/]/, '');
            let id = self.activePost.id;
            let urlEdit = id ? EDIT_LINK.replace("__id__", id) : ADD_LINK;

            ImageUploadService.init(cropperContentSize, PREVIEW_IMAGE, cropperContainer, dialogContentSize,
                $(this), fileName, null, null,
                function (formData) {
                    formData.append('description', self.activePost.description);

                    $.ajax(urlEdit, {
                        method: "POST",
                        data: formData,
                        processData: false,
                        contentType: false,
                        success(data) {
                            if(data.success) {
                                self.posts[data.gallery.id] = data.gallery;

                                $scope.$evalAsync();
                            }

                            closeModals();
                        },
                        error(data) {
                            console.error('Upload error');
                        },
                    });
                });

            console.log(self.activePost);

            if(eventUpload) {
                ImageUploadService.processUploadImage(eventUpload.target.files[0]);
            }
            else{
                PREVIEW_IMAGE.attr("src", self.activePost.path);
                ImageUploadService.addDialog();
            }
        }

        function closeModals() {
            $(cropperDialog).removeClass("modal--show");
            $("#dialog-cropper-container-gallery2").removeClass("modal--show");
            $("body").removeClass("modal--show");
        }

        function removePost(){
            $.ajax(REMOVE_LINK.replace("__rid__", self.activePost.id), {
                success(data) {
                    if(data.success) {
                        delete self.posts[self.activePost.id];
                        $scope.$evalAsync();
                    }

                    closeRemoveConfirm.click();
                },
                error(data) {
                    console.error('Upload error');

                    closeRemoveConfirm.click();
                },
            });
        }

        function getPosts() {
            $.ajax(ALL_POSTS_LINK, {
                method: "POST",
                success(data) {
                    self.posts = data;
                    $scope.$evalAsync();
                },
                error(data) {
                    console.error('Error due request');
                },
            });
        }

        function removeGalleryCar(id) {
            if(!id){
                return false;
            }

            $.ajax(REMOVE_GALLERY_CAR_LINK.replace("__id__", id), {
                method: "POST",
                success(data) {
                    if(data.success) {
                        self.posts[data.gallery.id] = data.gallery;

                        $scope.$evalAsync();
                    }
                },
                error(data) {
                    console.error('Error due request');
                },
            });
        }

        this.init = init;
        this.getNewPost = getNewPost;
        this.editPost = editPost;
        this.closeModals = closeModals;
        this.removePost = removePost;
        this.removeGalleryCar = removeGalleryCar;
    }]);
})(window.autoparusApp);