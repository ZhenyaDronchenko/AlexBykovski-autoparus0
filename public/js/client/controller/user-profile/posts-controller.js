(function(autoparusApp) {
    'use strict';

    autoparusApp.controller('PostsCtrl', ["$scope", "$http", "$compile", "$rootScope", "ImageUploadService",
        function($scope, $http, $compile, $rootScope, ImageUploadService) {
        const REMOVE_LINK = Routing.generate('posts_remove_post_ajax', {"id" : "__rid__"});
        const ADD_LINK = Routing.generate('posts_add_post_ajax');
        const EDIT_LINK = Routing.generate('posts_edit_post_ajax', {"id" : "__id__"});
        const ALL_POSTS_LINK = Routing.generate('posts_get_all_posts_ajax');
        const REMOVE_GALLERY_FILTER_LINK = Routing.generate('remove_post_filter_ajax', {"id" : "__id__", "filterId" : "__post_id__"});
        const ADD_POST_PHOTO_LINK = Routing.generate('posts_add_post_photo_ajax', {"id" : "__id__"});
        const EDIT_POST_PHOTO_LINK = Routing.generate('posts_edit_post_photo_ajax', {"id" : "__id__", "idPostPhoto" : "__id_post_photo__"});
        const PREVIEW_IMAGE = $("#image-preview-container-gallery img");
        const PREVIEW_IMAGE_POST_PHOTO = $("#image-preview-container-post-photo img");
        const SIMPLE_TYPE = "simple";

        let self = this;
        let closeRemoveConfirm = $("#close-popup-5");
        let dialogContentSize = window.screen.availWidth > window.screen.availHeight ? window.screen.availHeight : window.screen.availWidth;
        let cropperContentSize = dialogContentSize * 0.6;
        let cropperDialog = null;
        let cropperDialogPostPhoto = null;

        this.activePost = null;
        this.posts = {};

        function init(cropperDialogS, cropperDialogPostPhotoS, isUploadPosts) {
            cropperDialog = cropperDialogS;
            cropperDialogPostPhoto = cropperDialogPostPhotoS;

            if (isUploadPosts){
                getPosts();
            }
        }
        
        function getNewPost(type) {
            return {
                "address": "",
                "date": "",
                "description": "",
                "id": null,
                "images": [],
                "time": "",
                "type" : type ? type : SIMPLE_TYPE,
                "userId" : "",
            };
        }

        function editPost(eventUpload) {
            let cropperContainer = $(cropperDialog);
            let fileName = eventUpload ? eventUpload.target.files[0].name : self.activePost["images"][0]["path"].replace(/^.*[\\\/]/, '');
            let id = self.activePost["images"][0]["id"];
            let urlEdit = id ? EDIT_LINK.replace("__id__", id) : ADD_LINK;

            ImageUploadService.init(cropperContentSize, PREVIEW_IMAGE, cropperContainer, dialogContentSize,
                $(this), fileName, null, null,
                function (formData) {
                    formData.append('description', self.activePost.description);
                    formData.append('type', self.activePost.type);

                    $.ajax(urlEdit, {
                        method: "POST",
                        data: formData,
                        processData: false,
                        contentType: false,
                        success(data) {
                            if(data.success) {
                                self.posts[data.post.id] = data.post;

                                $scope.$evalAsync();
                            }

                            closeModals();
                        },
                        error(data) {
                            console.error('Upload error');
                        },
                    });
                });

            if(eventUpload) {
                ImageUploadService.processUploadImage(eventUpload.target.files[0]);
            }
            else{
                PREVIEW_IMAGE.attr("src", self.activePost["images"][0]["path"]);
                ImageUploadService.addDialog();
            }
        }

            function editPostPhoto(eventUpload) {
                let cropperContainer = $(cropperDialogPostPhoto);
                let fileName = eventUpload ? eventUpload.target.files[0].name : self.activePost["images"][0]["path"].replace(/^.*[\\\/]/, '');
                console.log(self.activePost);
                let id = self.activePost["images"][0]["id"];
                //let urlEdit = id ? EDIT_LINK.replace("__id__", id) : ADD_LINK;
                let urlEdit = ADD_POST_PHOTO_LINK.replace("__id__", self.activePost.id);

                ImageUploadService.init(cropperContentSize, PREVIEW_IMAGE_POST_PHOTO, cropperContainer, dialogContentSize,
                    $(this), fileName, null, null,
                    function (formData) {
                        $.ajax(urlEdit, {
                            method: "POST",
                            data: formData,
                            processData: false,
                            contentType: false,
                            success(data) {
                                if(data.success) {
                                    self.posts[self.activePost.id].images.push(data.postPhoto);

                                    $scope.$evalAsync();
                                }

                                closeModals();
                            },
                            error(data) {
                                console.error('Upload error');
                            },
                        });
                    });

                if(eventUpload) {
                    ImageUploadService.processUploadImage(eventUpload.target.files[0]);
                }
                else{
                    //PREVIEW_IMAGE_POST_PHOTO.attr("src", self.activePost["images"][0]["path"]);
                    ImageUploadService.addDialog();
                }
            }

        function closeModals() {
            $(cropperDialog).removeClass("modal--show");
            $(cropperDialogPostPhoto).removeClass("modal--show");
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

        function removeGalleryFilter(id, postId) {
            if(!id){
                return false;
            }

            $.ajax(REMOVE_GALLERY_FILTER_LINK.replace("__id__", id).replace("__post_id__", postId), {
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
        this.editPostPhoto = editPostPhoto;
        this.closeModals = closeModals;
        this.removePost = removePost;
        this.removeGalleryFilter = removeGalleryFilter;
    }]);
})(window.autoparusApp);