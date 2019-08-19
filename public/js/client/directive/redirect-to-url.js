(function(autoparusApp) {
    'use strict';

    autoparusApp.directive("redirectToUrl",[function(){
        return{
            restrict: 'A',
            link: function(scope, element, attrs)
            {
                const waitTime = attrs.redirectWait ? Number.parseInt(attrs.redirectWait) * 1000 : 0;

                setTimeout(function () {
                    window.location.href = attrs.redirectUrl;
                }, waitTime);
            }
        };
    }]);

})(window.autoparusApp);