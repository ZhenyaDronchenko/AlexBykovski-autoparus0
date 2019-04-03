(function(autoparusApp) {
    'use strict';

    autoparusApp.controller('MainPagePostsCtrl', ['$scope', '$http', '$sce', function($scope, $http, $sce) {
        const MAX_COUNT = 100;

        let self = this;
        let url = null;
        let params = {
            "limit" : 5,
            "offset" : 0,
        };
        this.posts = [];
        //image from https://loading.io/
        let preloader = $("#preloader-posts");

        function init(urlS){
            url = urlS;

            updatePosts();
        }

        function updatePosts() {
            if(params.offset + params.limit >= MAX_COUNT || preloader.is("visible")){
                return false;
            }

            params.offset = params.offset + params.limit;

            preloader.css("display", "block");

            $http({
                method: 'POST',
                url: url,
                data: params
            }).then(function (response) {
                $.each(response.data, function (index, post) {
                    $sce.trustAsHtml(post["description"]);

                    self.posts.push(post);
                });

                if(self.posts.length % 5 === 0){
                    updateScrollTrigger("#post-" + self.posts[self.posts.length - 2].id);
                }

                preloader.css("display", "none");

                console.log(self.posts.length);
            }, function (response) {
                console.log("error");
            });
        }

        function updateScrollTrigger(id){
            $(window).on("scroll", function() {
                console.log(id);
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

    }]);
})(window.autoparusApp);