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
                        el.slick({
                            dots: el.find("[carousel-item]").length > 1,
                        });

                        const NEXT_MODE_SLIDE = el.attr("data-move-slide");

                        if(NEXT_MODE_SLIDE){
                            el.slick("slickGoTo", NEXT_MODE_SLIDE);
                        }
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