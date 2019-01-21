(function(autoparusApp) {
    'use strict';

    autoparusApp.directive("passwordShowButton",[function(){
        return{
            restrict: 'A',
            scope: true,
            link: function(scope, element, attrs)
            {
                let target = $(attrs.targetElement);

                scope.changeState = function(){
                    if(target.attr("type") === "text"){
                        target.attr("type", "password");
                    }
                    else{
                        target.attr("type", "text");
                    }

                    return false;
                };
            }
        };
    }]);

})(window.autoparusApp);