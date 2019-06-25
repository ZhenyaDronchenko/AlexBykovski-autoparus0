(function (autoparusApp) {
    'use strict';

    autoparusApp.directive("carouselElement", ['$rootScope', function($rootScope) {
        return {
            restrict: 'A',
            transclude: false,
            link: function (scope, element, attrs) {
                scope.initCarousel = function(element) {
                    let el = $(element);

                    if(!el.hasClass("slick-initialized")){
                        el.slick({});

                        el.slick("slickGoTo", el.attr("data-move-slide"));
                    }

                    el.on('beforeChange', function (event, slick, direction) {
                         if(direction === 0){
                             $rootScope.$broadcast("start-slide-post-images", {
                                 id: attrs.postId
                             });

                             $(this).off("beforeChange");
                         }
                     });
                };
            }
        };
    }]);

    autoparusApp.directive("carouselItem", [function () {
        return {
            restrict: 'A',
            transclude: false,

            link: function (scope, element, attrs) {
                if(scope.$last) {
                    scope.initCarousel(element.parent());
                }
            }
        };
    }]);



})(window.autoparusApp);