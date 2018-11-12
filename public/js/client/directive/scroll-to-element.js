(function(autoparusApp) {
    'use strict';

    autoparusApp.directive("scrollToElement",[function(){
        return{
            restrict: 'A',
            link: function(scope, element, attrs)
            {
                $(document).ready(function(ev){
                    $('html, body').animate({
                        scrollTop: $(element).offset().top
                    }, 2000);
                });
            }
        };
    }]);

})(window.autoparusApp);