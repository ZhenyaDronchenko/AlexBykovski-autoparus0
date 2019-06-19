(function (autoparusApp) {
    'use strict';

    autoparusApp.directive("carouselElement", function() {
        return {
            restrict: 'A',
            transclude: false,
            link: function (scope) {
                scope.initCarousel = function(element) {
                    // provide any default options you want
                    var defaultOptions = {
                    };
                    var customOptions = scope.$eval($(element).attr('data-options'));
                    // combine the two options objects
                    for(var key in customOptions) {
                        defaultOptions[key] = customOptions[key];
                    }
                    //console.log(defaultOptions);
                    // init carousel
                    var curOwl = $(element).data('owlCarousel');

                    if(!angular.isDefined(curOwl)) {
                        //console.log($(element).hasClass("slick-initialized"));
                        if($(element).hasClass("slick-initialized")){
                            console.log("HJHJH");
                            $(element).slick("unslick");
                        }

                        $(element).slick({
                            //fade: true,
                            //touchMove: true,
                        });
                        // if($(element).hasClass("owl-drag")){
                        //     console.log("remove");
                        //     //$(element).trigger('refresh.owl.carousel');
                        //     //$(element).owlCarousel(defaultOptions);
                        //     $(element).trigger('destroy.owl.carousel');
                        //     $(element).html($(element).find('.owl-stage-outer').html()).removeClass('owl-loaded');
                        //     //$(element).owlCarousel(defaultOptions);
                        // }
                        // else{
                        //     console.log("crrrr");
                        //     $(element).owlCarousel(defaultOptions);
                        // }
                    }
                };
            }
        };
    })

    autoparusApp.directive("carouselItem", [function () {
        return {
            restrict: 'A',
            //scope: true,
            transclude: false,

            link: function (scope, element, attrs) {
                if(scope.$last) {
                    scope.initCarousel(element.parent());
                }
                // $(element).owlCarousel({
                //     "center" : true,
                //     "items" : 2,
                //     "loop" : true,
                //     "margin" : 10,
                //     "responsive" : {
                //         "576" : {"items" : 3}, "768" : {"items" : 4}, "951" : {"items" : 5}, "1200" : {"items" : 6}}});
            }
        };
    }]);



})(window.autoparusApp);