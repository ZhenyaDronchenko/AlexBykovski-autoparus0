(function(autoparusApp) {
    'use strict';

    autoparusApp.controller('PostsCtrl', ["$scope", "$http", "$compile", "$rootScope", "ImageUploadService",
        function($scope, $http, $compile, $rootScope, ImageUploadService) {
        const REMOVE_LINK = Routing.generate('posts_remove_post_ajax', {"id" : "__rid__"});
        const REMOVE_POST_PHOTO_LINK = Routing.generate('posts_remove_post_photo_ajax', {"id" : "__rid__"});
        const ADD_LINK = Routing.generate('posts_add_post_ajax');
        const EDIT_LINK = Routing.generate('posts_edit_post_ajax', {"id" : "__id__"});
        const GET_POSTS_LINK = Routing.generate('posts_get_posts_ajax');
        const REMOVE_GALLERY_FILTER_LINK = Routing.generate('remove_post_filter_ajax', {"postId" : "__post_id__", "filterId" : "__id__"});
        const ADD_POST_PHOTO_LINK = Routing.generate('posts_add_post_photo_ajax', {"id" : "__id__"});
        const EDIT_POST_HEADLINE = Routing.generate('posts_save_post_headline_ajax', {"id" : "__id__"});
        const PREVIEW_IMAGE = $("#image-preview-container-gallery img");
        const PREVIEW_IMAGE_POST_PHOTO = $("#image-preview-container-post-photo img");
        const SIMPLE_TYPE = "simple";
        const COUNT_IMAGE_PACKAGE = 10;
        const PREVIEW_IMAGE_SELECTOR = ".preloader-image";

        let self = this;
        let closeRemoveConfirm = $("#close-popup-5");
        let closeRemoveFilterConfirm = $('.close-popup-button[data-popup-id="9"]');
        let dialogContentSize = window.screen.availWidth > window.screen.availHeight ? window.screen.availHeight : window.screen.availWidth;
        let cropperContentSize = dialogContentSize * 0.6;
        let cropperDialog = null;
        let cropperDialogPostPhoto = null;
        let preloader = $("#preloader-posts");

        let paramsSearchPosts = {
            "limit" : 2,
            "offset" : 0,
        };

        this.activePost = null;
        this.activeFilter = null;
        this.posts = [];

        function init(cropperDialogS, cropperDialogPostPhotoS, isUploadPosts) {
            cropperDialog = cropperDialogS;
            cropperDialogPostPhoto = cropperDialogPostPhotoS;

            if (isUploadPosts){
                updatePosts();
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
            let fileName = getFileNameEditPost(eventUpload);
            let id = !eventUpload ? self.activePost["images"][0]["id"] : eventUpload;
            let urlEdit = Number.isInteger(id) ? EDIT_LINK.replace("__id__", id) : ADD_LINK;

            if(!fileName){
                return false;
            }

            ImageUploadService.init(cropperContentSize, PREVIEW_IMAGE, cropperContainer, dialogContentSize,
                $(this), fileName, null, null,
                function (formData) {
                    formData.append('description', self.activePost.description ? self.activePost.description : "");
                    formData.append('type', self.activePost.type);
                    cropperContainer.parent().find(PREVIEW_IMAGE_SELECTOR).show();

                    $.ajax(urlEdit, {
                        method: "POST",
                        data: formData,
                        processData: false,
                        contentType: false,
                        success(data) {
                            cropperContainer.parent().find(PREVIEW_IMAGE_SELECTOR).hide();

                            if(data.success) {
                                data.post["moveSlide"] = id ? getPhotoIndexById(id) : 0;
                                self.posts[data.post.id] = data.post;

                                $scope.$evalAsync();
                            }

                            closeModals();
                        },
                        error(data) {
                            cropperContainer.parent().find(PREVIEW_IMAGE_SELECTOR).hide();

                            console.error('Upload error');
                        },
                    });
                });

            if(Number.isInteger(eventUpload) || !eventUpload){
                PREVIEW_IMAGE.attr("src", getExistImageEditPost(eventUpload));
                ImageUploadService.addDialog();
            }
            else{
                ImageUploadService.processUploadImage(eventUpload.target.files[0]);
            }
        }

        function addPostPhoto(eventUpload) {
            if(!eventUpload){
                return false;
            }

            let cropperContainer = $(cropperDialogPostPhoto);
            let fileName = eventUpload ? eventUpload.target.files[0].name : self.activePost["images"][0]["path"].replace(/^.*[\\\/]/, '');
            let urlEdit = ADD_POST_PHOTO_LINK.replace("__id__", self.activePost.id);

            ImageUploadService.init(cropperContentSize, PREVIEW_IMAGE_POST_PHOTO, cropperContainer, dialogContentSize,
                $(this), fileName, null, null,
                function (formData) {
                    cropperContainer.parent().find(PREVIEW_IMAGE_SELECTOR).show();

                    $.ajax(urlEdit, {
                        method: "POST",
                        data: formData,
                        processData: false,
                        contentType: false,
                        success(data) {
                            cropperContainer.parent().find(PREVIEW_IMAGE_SELECTOR).hide();

                            if(data.success) {
                                $('.post-images-' + self.activePost.id).trigger('destroy.owl.carousel');

                                showAllPostPhotos(self.posts[self.activePost.id]);
                                self.posts[self.activePost.id].images.push(data.postPhoto);
                                self.posts[self.activePost.id].moveSlide = self.posts[self.activePost.id].images.length - 1;

                                $scope.$evalAsync();
                            }

                            closeModals();
                        },
                        error(data) {
                            cropperContainer.parent().find(PREVIEW_IMAGE_SELECTOR).hide();

                            console.error('Upload error');
                        },
                    }
                );
            });

            ImageUploadService.processUploadImage(eventUpload.target.files[0]);
        }

        function closeModals() {
            $(cropperDialog).removeClass("modal--show");
            $(cropperDialogPostPhoto).removeClass("modal--show");
            $("#dialog-cropper-container-gallery2").removeClass("modal--show");
            $("body").removeClass("modal--show");
        }

        function removePost(removePhotoId){
            let link = removePhotoId ? REMOVE_POST_PHOTO_LINK.replace("__rid__", removePhotoId) :
                REMOVE_LINK.replace("__rid__", self.activePost.id);

            $.ajax(link, {
                success(data) {
                    if(data.success) {
                        if(removePhotoId && data.post){
                            self.posts[data.post.id] = data.post;
                        }
                        else {
                            delete self.posts[self.activePost.id];
                        }

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

        function updatePosts() {
            if(paramsSearchPosts.offset + paramsSearchPosts.limit > (Object.keys(self.posts).length + 12) || preloader.is("visible")){
                return false;
            }

            preloader.css("display", "block");

            $http({
                method: 'POST',
                url: GET_POSTS_LINK,
                data: paramsSearchPosts
            }).then(function (data) {
                handleUploadedPosts(data.data);

                $scope.$evalAsync();
            }, function (data) {
                console.error('Error due request');
            });
        }

        function handleUploadedPosts(data) {
            for(let id in data){
                if(!self.posts.hasOwnProperty(id)){
                    self.posts[id] = waitImagesPost(data[id]);
                }
            }

            paramsSearchPosts["offset"] = paramsSearchPosts["limit"] + paramsSearchPosts["offset"];
            paramsSearchPosts["limit"] = COUNT_IMAGE_PACKAGE;

            const keys = Object.keys(self.posts);

            if(keys.length > 1){
                updateScrollTrigger("#post-" + self.posts[keys[keys.length - 2]].id);
            }

            preloader.css("display", "none");
        }

        function removeGalleryFilter(id, postId) {
            if(!id){
                return false;
            }

            $.ajax(REMOVE_GALLERY_FILTER_LINK.replace("__id__", id).replace("__post_id__", postId), {
                method: "POST",
                success(data) {
                    if(data.success) {
                        self.posts[data.post.id] = data.post;

                        $scope.$evalAsync();

                        closeRemoveFilterConfirm.click();
                    }
                },
                error(data) {
                    console.error('Error due request');
                },
            });
        }

        //@@todo add handler if don't save on server
        function savePostHeadline(post) {
            $.ajax(EDIT_POST_HEADLINE.replace("__id__", post.id), {
                data: {
                    headline: post.headline
                },
                method: "POST",
                success(data) {
                    if(data.success) {
                        self.openChangeHeadline[post.id] = false;

                        $scope.$evalAsync();
                    }
                },
                error(data) {
                    console.error('Error due request');
                },
            });
        }

        function getFileNameEditPost(eventUpload) {
            if(Number.isInteger(eventUpload) || !eventUpload){
                let fullPath = getExistImageEditPost(eventUpload);

                return fullPath ? fullPath.replace(/^.*[\\\/]/, '') : false;
            }

            return eventUpload.target.files[0].name;
        }

        function getExistImageEditPost(photoId) {
            if(!Number.isInteger(photoId)){
                return self.activePost["images"].length ? self.activePost["images"][0]["path"] : "";
            }

            for (let index in self.activePost["images"]) {
                if(self.activePost["images"][index]["id"] === photoId){
                    return self.activePost["images"][index]["path"];
                }
            }

            return false;
        }

        function getPhotoIndexById(photoId) {
            if(!Number.isInteger(photoId)){
                return 0;
            }

            for (let index in self.activePost["images"]) {
                if(self.activePost["images"][index]["id"] === photoId){
                    return index;
                }
            }

            return 0;
        }

        $rootScope.$on("start-slide-post-images", function(event, args) {
            if(self.posts.hasOwnProperty(args.id)) {
                showAllPostPhotos(self.posts[args.id]);

                $scope.$evalAsync();
            }
        });

        function updateScrollTrigger(id){
            $(window).on("scroll", function() {
                let hT = $(id).offset().top;
                let hH = $(id).outerHeight();
                let wH = $(window).height();
                let wS = $(this).scrollTop();

                if (wS > (hT+hH-wH)){
                    updatePosts();

                    $(window).off();
                }
            });
        }

        this.init = init;
        this.getNewPost = getNewPost;
        this.editPost = editPost;
        this.addPostPhoto = addPostPhoto;
        this.closeModals = closeModals;
        this.removePost = removePost;
        this.removeGalleryFilter = removeGalleryFilter;
        this.savePostHeadline = savePostHeadline;
    }]);
})(window.autoparusApp);