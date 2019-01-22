(function(autoparusApp) {
    'use strict';

    autoparusApp.directive("inputByPattern",[function(){
        return{
            restrict: 'A',
            scope: true,
            link: function(scope, element, attrs)
            {
                let pattern = new RegExp(attrs.pattern);
                let errorMessage = attrs.errorMessage;
                let errorContainer = attrs.errorContainer ? $(attrs.errorContainer) : "";

                let maxLength = attrs.maxLength ? parseInt(attrs.maxLength) : Number.MAX_VALUE;
                maxLength = maxLength < 1 ? 0 : maxLength;

                $(element).on("keypress", function(ev){
                    if(!pattern.test(ev.key) || $(element).val().length === maxLength) {
                        ev.preventDefault();

                       if(errorContainer && errorContainer.length && errorMessage){
                           errorContainer.html(errorMessage);
                       }
                    }
                    else if(errorContainer && errorContainer.length){
                        errorContainer.html("");
                    }
                })
            }
        };
    }]);

})(window.autoparusApp);