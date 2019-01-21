(function(autoparusApp) {
    'use strict';

    autoparusApp.directive("scrollToElementStartInput",[function(){
        return{
            restrict: 'A',
            scope: true,
            link: function(scope, element, attrs)
            {
                let target = $(attrs.targetElement);

                $(element).on("focus", function(ev){
                    if(target[0].getBoundingClientRect().top > 0){
                        $(document).ready(function(ev){
                            $('html, body').animate({
                                scrollTop: target.offset().top
                            }, 2000);
                        });
                    }
                });
            }
        };
    }]);

})(window.autoparusApp);