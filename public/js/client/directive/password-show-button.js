(function(autoparusApp) {
    'use strict';

    autoparusApp.directive("passwordShowButton",[function(){
        return{
            restrict: 'A',
            scope: true,
            link: function(scope, element, attrs)
            {
                scope.changeState = function(){
                    let target = $(attrs.targetElement);

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