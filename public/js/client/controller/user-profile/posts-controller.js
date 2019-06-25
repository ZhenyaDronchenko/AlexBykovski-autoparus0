(function(autoparusApp) {
    'use strict';

    autoparusApp.controller('PostsCtrl', ["$scope", "$http", "$compile", "$rootScope", "ImageUploadService",
        function($scope, $http, $compile, $rootScope, ImageUploadService) {
        const REMOVE_LINK = Routing.generate('posts_remove_post_ajax', {"id" : "__rid__"});
        const REMOVE_POST_PHOTO_LINK = Routing.generate('posts_remove_post_photo_ajax', {"id" : "__rid__"});
        const ADD_LINK = Routing.generate('posts_add_post_ajax');
        const EDIT_LINK = Routing.generate('posts_edit_post_ajax', {"id" : "__id__"});
        const GET_POSTS_LINK = Routing.generate('posts_get_posts_ajax');
        const REMOVE_GALLERY_FILTER_LINK = Routing.generate('remove_post_filter_ajax', {"id" : "__id__", "filterId" : "__post_id__"});
        const ADD_POST_PHOTO_LINK = Routing.generate('posts_add_post_photo_ajax', {"id" : "__id__"});
        const EDIT_POST_HEADLINE = Routing.generate('posts_save_post_headline_ajax', {"id" : "__id__"});
        const PREVIEW_IMAGE = $("#image-preview-container-gallery img");
        const PREVIEW_IMAGE_POST_PHOTO = $("#image-preview-container-post-photo img");
        const SIMPLE_TYPE = "simple";
        const COUNT_IMAGE_PACKAGE = 10;

        let self = this;
        let closeRemoveConfirm = $("#close-popup-5");
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

                    $.ajax(urlEdit, {
                        method: "POST",
                        data: formData,
                        processData: false,
                        contentType: false,
                        success(data) {
                            if(data.success) {
                                data.post["moveSlide"] = id ? getPhotoIndexById(id) : 0;
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
                    $.ajax(urlEdit, {
                        method: "POST",
                        data: formData,
                        processData: false,
                        contentType: false,
                        success(data) {
                            if(data.success) {
                                let countTempImages = Object.keys(self.posts[self.activePost.id].tempImages).length;

                                self.posts[self.activePost.id].images.push(data.postPhoto);
                                self.posts[self.activePost.id].tempImages[countTempImages] = data.postPhoto;
                                self.posts[self.activePost.id].moveSlide = self.posts[self.activePost.id].images.length - 1;

                                $('.post-images-' + self.activePost.id).slick("unslick");

                                $scope.$evalAsync();
                            }

                            closeModals();
                        },
                        error(data) {
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
                    self.posts[id] = data[id];
                    waitImages([self.posts[id]]);
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
                        self.posts[data.gallery.id] = data.gallery;

                        $scope.$evalAsync();
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
        
        function waitImages(posts) {
            $.each(posts, function (index, item) {
                posts[index] = waitImagesPost(posts[index]);
            });

            return posts;
        }

        $rootScope.$on("start-slide-post-images", function(event, args) {
            showAllPostPhotos(args.id);
        });

        function waitImagesPost(post) {
            post["tempImages"] = {};

            $.each(post["images"], function (indexIm) {
                post["tempImages"][indexIm] = {
                    id: post["images"][indexIm]["id"],
                    path: post["images"][indexIm]["path"],
                };

                if(indexIm !== 0){
                    post["images"][indexIm]["path"] = "";
                }
            });

            return post;
        }

        function showAllPostPhotos(id) {
            if(self.posts.hasOwnProperty(id) && self.posts[id].hasOwnProperty("tempImages")){
                $.each(self.posts[id]["images"], function (index) {
                    self.posts[id]["images"][index]["path"] = self.posts[id]["tempImages"][index]["path"];
                });

                $scope.$evalAsync();
            }
        }

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