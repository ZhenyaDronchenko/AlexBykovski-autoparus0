(function(autoparusApp) {
    'use strict';

    autoparusApp.directive("showHint",[function(){
        return{
            restrict: 'A',
            scope: true,
            link: function(scope, element, attrs)
            {
                let triggerOpen = attrs.triggerOpen;
                let triggerClose = attrs.triggerClose;

                $("body")
                    .on("click", attrs.triggerOpen, function(ev){
                        $(element).show();
                    })
                    .on("click", attrs.triggerClose, function(ev){
                        $(element).hide();
                    });
            }
        };
    }]);

})(window.autoparusApp);