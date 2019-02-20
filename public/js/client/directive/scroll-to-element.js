(function(autoparusApp) {
    'use strict';

    autoparusApp.directive("scrollToElement",[function(){
        return{
            restrict: 'A',
            link: function(scope, element, attrs)
            {
                let autoFocus = attrs.autoFocus ? $(attrs.autoFocus) : null;
                let isScrollByClick = !!attrs.scrollByClick;
                let initiator = attrs.initiator ? $(attrs.initiator) : null;

                $(document).ready(function(ev){
                    if(!isScrollByClick){
                        scroll();
                    }
                    else {
                        initiator.click(function (ev) {
                            scroll();
                        });
                    }
                });

                function scroll(){
                    console.log("scroll");
                    $('html, body').animate({
                        scrollTop: $(element).offset().top
                    }, 1000, "swing", function(){
                        if(autoFocus && !autoFocus.is(":focus")){
                            //@@todo: not working for dynamic add elements
                            autoFocus.focus();
                        }
                    });
                }
            }
        };
    }]);

})(window.autoparusApp);