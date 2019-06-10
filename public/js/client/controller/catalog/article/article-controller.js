(function(autoparusApp) {
    'use strict';

    autoparusApp.controller('ArticleCtrl', ['$scope', '$http', '$sce', function($scope, $http, $sce) {
        const MAX_COUNT = 100;
        let self = this;
        let url = null;
        let params = {
            "limit" : 9,
            "offset" : -9,
        };
        let urlToArticle = "";
        let articlesPerPackage = 9;

        this.articles = [];

        //image from https://loading.io/
        let preloader = $("#preloader-articles");

        function init(urlS, paramsS, urlToArticleS, articlesPerPackageS){
            url = urlS;
            Object.assign(params, angular.fromJson(paramsS));
            urlToArticle = urlToArticleS;
            articlesPerPackage = articlesPerPackageS;

            params = {
                "limit" : articlesPerPackage,
                "offset" : -articlesPerPackage,
            };

            updateArticles();
        }

        function updateArticles() {
            if(params.offset + params.limit >= MAX_COUNT || params.offset + params.limit > self.articles.length || preloader.is("visible")){
                return false;
            }

            params.offset = params.offset + params.limit;

            preloader.css("display", "block");

            $http({
                method: 'POST',
                url: url,
                data: params
            }).then(function (response) {
                $.each(response.data, function (index, article) {
                    $sce.trustAsHtml(article["mainImage"]["text"]);

                    self.articles.push(article);
                });

                if(self.articles.length % articlesPerPackage === 0 && self.articles.length){
                    updateScrollTrigger("#article-" + self.articles[self.articles.length - 2].id);
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
                    updateArticles();

                    $(window).off();
                }
            });
        }

        function getArticleUrl(article) {
            return urlToArticle.replace("__theme__", article.themes[0].url).replace("__id__", article.id);
        }

        this.init = init;
        this.getArticleUrl = getArticleUrl;

    }]);
})(window.autoparusApp);