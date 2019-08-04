(function(autoparusApp) {
    'use strict';

    autoparusApp.controller('MainPagePostsCtrl', ['$scope', '$http', '$sce', '$rootScope',
        function($scope, $http, $sce, $rootScope) {
        const MAX_COUNT = 106;
        let filterUserRoute = "homepage_filter_user";
        let filterBrandRoute = "homepage_filter_brand";
        let filterBrandModelRoute = "homepage_filter_brand_model";
        let filterCityActivityRoute = "homepage_filter_city_activity";

        let self = this;
        let url = null;
        let params = {
            "limit" : 2,
            "offset" : -2,
        };
        let countFromNotPremium = 0;

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
            if(params.offset + params.limit >= MAX_COUNT || params.offset + params.limit > self.posts.length - countFromNotPremium || preloader.is("visible")){
                return false;
            }

            params.offset = params.offset + params.limit;

            preloader.css("display", "block");

            $http({
                method: 'POST',
                url: url,
                data: params
            }).then(function (response) {
                if(params.hasOwnProperty("notPremium")){
                    countFromNotPremium = response.data.length;
                }

                $.each(response.data, function (index, post) {
                    $sce.trustAsHtml(post["description"]);

                    self.posts.push(waitImagesPost(post));
                });

                if(((self.posts.length - 2) % 4 === 0 || params.limit === 2 && self.posts.length === 2) && self.posts.length > 1){
                    const startScrollIndex = self.posts.length > 2 ? self.posts.length - 3 : self.posts.length - 2;

                    updateScrollTrigger("#post-" + self.posts[startScrollIndex].id);
                }

                updateParams();

                preloader.css("display", "none");
            }, function (response) {
                console.log("error");
            });
        }

        function updateParams() {
            if(params.limit === 2){
                if(location.pathname === '/' && !params.hasOwnProperty("notPremium")){
                    params.limit = 4;
                    params.offset = -4;
                    params["notPremium"] = "notPremium";
                }
                else{
                    delete params["notPremium"];
                    params.limit = 4;
                    params.offset = -2;
                }
            }
            else if(params.limit === 4 && params.hasOwnProperty("notPremium") && location.pathname === '/'){
                delete params["notPremium"];
                params.limit = 4;
                params.offset = -2;
            }
        }

        function updateScrollTrigger(id){
            $(id).ready(function () {
                let hT = $(id).offset().top;
                let wH = $(window).height();
                let wS = $(this).scrollTop();

                if (hT < (wH + wS)){
                    return updatePosts();
                }

                $(window).on("scroll", function() {
                    if($(id).length < 1){
                        return false;
                    }

                    let hT = $(id).offset().top;
                    let wH = $(window).height();
                    let wS = $(this).scrollTop();

                    if (hT < (wH + wS)){
                        updatePosts();

                        $(window).off("scroll");
                    }
                });
            });
        }

        function getUserFilterUrl(userId) {
            return Routing.generate(filterUserRoute, {"userId" : userId});
        }

        function moveToPost(id, type){
            if(type === "simple"){
                return Routing.generate("post_view_show_car_post", {"id" : id});
            }

            return Routing.generate("post_view_show_business_post", {"id" : id});
        }

        $rootScope.$on("start-slide-post-images", function(event, args) {
            for (let index in self.posts){
                if(Number.parseInt(self.posts[index].id) === Number.parseInt(args.id)){
                    showAllPostPhotos(self.posts[index]);
                }
            }

            $scope.$evalAsync();
        });

        this.init = init;
        this.getUserFilterUrl = getUserFilterUrl;
        this.moveToPost = moveToPost;

    }]);
})(window.autoparusApp);