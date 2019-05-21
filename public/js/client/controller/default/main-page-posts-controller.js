(function(autoparusApp) {
    'use strict';

    autoparusApp.controller('MainPagePostsCtrl', ['$scope', '$http', '$sce', function($scope, $http, $sce) {
        const MAX_COUNT = 100;
        let filterUserRoute = "homepage_filter_user";
        let filterBrandRoute = "homepage_filter_brand";
        let filterBrandModelRoute = "homepage_filter_brand_model";
        let filterCityActivityRoute = "homepage_filter_city_activity";

        let self = this;
        let url = null;
        let params = {
            "limit" : 5,
            "offset" : -5,
        };
        this.posts = [];
        //image from https://loading.io/
        let preloader = $("#preloader-posts");

        function init(urlS, paramsS){
            url = urlS;
            Object.assign(params, angular.fromJson(paramsS));

            if(params.hasOwnProperty("allUsers")){
                filterUserRoute = "homepage_filter_user_all_users";
                filterBrandRoute = "homepage_filter_brand_all_users";
                filterBrandModelRoute = "homepage_filter_brand_model_all_users";
                filterCityActivityRoute = "homepage_filter_city_activity_all_users";
            }

            updatePosts();
        }

        function updatePosts() {
            if(params.offset + params.limit >= MAX_COUNT || params.offset + params.limit > self.posts.length || preloader.is("visible")){
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

                if(self.posts.length % 5 === 0 && self.posts.length){
                    updateScrollTrigger("#post-" + self.posts[self.posts.length - 2].id);
                }

                preloader.css("display", "none");
            }, function (response) {
                console.log("error");
            });
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

        function getUserFilterUrl(userId) {
            return Routing.generate(filterUserRoute, {"userId" : userId})
        }

        function getBrandModelFilterUrl(urlBrand, urlModel) {
            if(!urlBrand){
                return "";
            }

            if(!urlModel){
                return Routing.generate(filterBrandRoute, {"urlBrand" : urlBrand})
            }

            return Routing.generate(filterBrandModelRoute, {"urlBrand" : urlBrand, "urlModel" : urlModel})
        }

        function getCityActivityFilterUrl(urlCity, urlActivity) {
            if(!urlCity || !urlActivity){
                return "";
            }

            return Routing.generate(filterCityActivityRoute, {"urlCity" : urlCity, "urlActivity" : urlActivity})
        }

        this.init = init;
        this.getUserFilterUrl = getUserFilterUrl;
        this.getBrandModelFilterUrl = getBrandModelFilterUrl;
        this.getCityActivityFilterUrl = getCityActivityFilterUrl;

    }]);
})(window.autoparusApp);