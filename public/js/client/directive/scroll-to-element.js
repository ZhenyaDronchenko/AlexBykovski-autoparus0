(function(autoparusApp) {
    'use strict';

    autoparusApp.directive("scrollToElement",[function(){
        return{
            restrict: 'A',
            link: function(scope, element, attrs)
            {
                let autoFocus = attrs.autoFocus ? $(attrs.autoFocus) : null;

                $(document).ready(function(ev){
                    $('html, body').animate({
                        scrollTop: $(element).offset().top
                    }, 1000, "swing", function(){
                        if(autoFocus && !autoFocus.is(":focus")){
                            //@@todo: not working for dynamic add elements
                            autoFocus.focus();
                        }
                    });
                });
            }
        };
    }]);

})(window.autoparusApp);