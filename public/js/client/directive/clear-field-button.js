(function(autoparusApp) {
    'use strict';

    autoparusApp.directive("clearFieldButton",[function(){
        return{
            restrict: 'A',
            link: function(scope, element, attrs)
            {
                let target = $(attrs.targetElement);

                $(element).click(function(ev){
                    target.val("");
                })
            }
        };
    }]);

})(window.autoparusApp);