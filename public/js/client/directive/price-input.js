(function(autoparusApp) {
    'use strict';

    autoparusApp.directive("priceInput",[function(){
        return{
            restrict: 'A',
            link: function(scope, element, attrs)
            {
                $(element).on("keypress", function(ev){
                    if("0123456789.".indexOf(ev.key) < 0) {
                        ev.preventDefault();
                    }
                })
            }
        };
    }]);

})(window.autoparusApp);