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

                    handleMaxLengthMessage();
                });

                $(element).on("keyup", function(e) {
                        if (e.keyCode === 8) {
                            handleMaxLengthMessage();
                        }
                    }
                );

                function handleMaxLengthMessage() {
                    let el = $(attrs.maxLengthMessage);

                    if(!el.length){
                        return false;
                    }

                    if($(element).val().length === maxLength){
                        el.show();
                    }
                    else if($(element).val().length < maxLength){
                        el.hide();
                    }
                }
            }
        };
    }]);

})(window.autoparusApp);