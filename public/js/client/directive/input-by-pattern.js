(function(autoparusApp) {
    'use strict';

    autoparusApp.directive("inputByPattern",[function(){
        return{
            restrict: 'A',
            link: function(scope, element, attrs)
            {
                let pattern = new RegExp(attrs.pattern);
                let maxLength = attrs.maxLength ? parseInt(attrs.maxLength) : Number.MAX_VALUE;
                maxLength = maxLength < 1 ? 0 : maxLength;

                $(element).on("keypress", function(ev){
                    if(!pattern.test(ev.key) || $(element).val().length === maxLength) {
                        ev.preventDefault();
                    }
                })
            }
        };
    }]);

})(window.autoparusApp);