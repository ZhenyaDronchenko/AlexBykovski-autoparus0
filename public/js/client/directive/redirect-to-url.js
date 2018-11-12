(function(autoparusApp) {
    'use strict';

    autoparusApp.directive("redirectToUrl",[function(){
        return{
            restrict: 'A',
            link: function(scope, element, attrs)
            {
                window.location.href = attrs.redirectUrl;
            }
        };
    }]);

})(window.autoparusApp);