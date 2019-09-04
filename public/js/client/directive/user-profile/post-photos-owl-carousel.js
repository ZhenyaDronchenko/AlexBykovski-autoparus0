(function (autoparusApp) {
    'use strict';

    autoparusApp.directive("carouselElement", ['$rootScope', function($rootScope) {
        return {
            restrict: 'A',
            transclude: false,
            link: function (scope, element, attrs) {
                scope.initCarousel = function(element) {
                    let el = $(element);

                    if(el.hasClass("owl-type")){
                        setOwlCarousel();
                    }
                    else {
                        setSlickCarousel();
                    }

                    function setSlickCarousel() {
                        if(!el.hasClass("slick-initialized")){
                            el.slick({
                                dots: el.find("[carousel-item]").length > 1,
                                variableWidth: true,
                            });

                            const NEXT_MOVE_SLIDE = el.attr("data-move-slide");

                            if(NEXT_MOVE_SLIDE){
                                el.slick("slickGoTo", NEXT_MOVE_SLIDE);
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
                    }

                    function setOwlCarousel() {
                        if(!el.hasClass("owl-loaded")){
                            const isMultiple = el.find("[carousel-item]").length > 1;

                            let carousel = el.owlCarousel({
                                items : 1,
                                dots: isMultiple,
                                loop : isMultiple,
                            });

                            carousel.on('changed.owl.carousel', function(e) {
                                $rootScope.$broadcast("start-slide-post-images", {
                                    id: attrs.postId
                                });

                                 $(this).off("changed.owl.carousel");
                                 $(this).trigger('refresh.owl.carousel');
                            });
                        }
                        else{
                            el.trigger('refresh.owl.carousel');
                        }

                        const NEXT_MOVE_SLIDE = el.attr("data-move-slide");

                        if(NEXT_MOVE_SLIDE){
                            el.trigger('to.owl.carousel', [NEXT_MOVE_SLIDE, 1000]);
                            el.trigger('refresh.owl.carousel');
                        }
                    }
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