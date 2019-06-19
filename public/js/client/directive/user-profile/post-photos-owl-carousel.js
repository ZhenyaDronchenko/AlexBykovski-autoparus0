(function (autoparusApp) {
    'use strict';

    autoparusApp.directive("carouselElement", ['$rootScope', function($rootScope) {
        return {
            restrict: 'A',
            transclude: false,
            link: function (scope, element, attrs) {
                scope.initCarousel = function(element) {
                    // provide any default options you want
                    var defaultOptions = {
                    };
                    var customOptions = scope.$eval($(element).attr('data-options'));
                    for(var key in customOptions) {
                        defaultOptions[key] = customOptions[key];
                    }

                    var curOwl = $(element).data('owlCarousel');

                    if(!angular.isDefined(curOwl)) {
                        if($(element).hasClass("slick-initialized")){
                            console.log("HJHJH");
                            $(element).slick("unslick");
                        }

                        $(element).slick({
                            //infinite: false,
                        });

                        $(element).on('beforeChange', function (event, slick, direction) {
                             if(direction === 0){
                                 $rootScope.$broadcast("start-slide-post-images", {
                                     id: attrs.postId
                                 });

                                 $(this).off("beforeChange");
                             }
                         });

                        // if(!$(element).hasClass("slick-initialized")) {
                        //     $(element).slick({
                        //         infinite: false,
                        //     });
                        //
                        //     $(element).on('afterChange', function (event, slick, direction) {
                        //         console.log("afterCHnage");
                        //         // left
                        //     });
                        //
                        //     $(element).on('beforeChange', function (event, slick, direction) {
                        //         console.log("beforeChange");
                        //         // left
                        //     });
                        //     $(element).on('reInit', function (event, slick, direction) {
                        //         console.log("reInit");
                        //         // left
                        //     });
                        // }
                    }
                };
            }
        };
    }]);

    autoparusApp.directive("carouselItem", [function () {
        return {
            restrict: 'A',
            //scope: true,
            transclude: false,

            link: function (scope, element, attrs) {
                if(scope.$last) {
                    scope.initCarousel(element.parent());
                }
            }
        };
    }]);



})(window.autoparusApp);