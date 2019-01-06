(function(autoparusApp) {
    'use strict';

    autoparusApp.directive("notFoundPageSearch",[function(){
        return{
            restrict: 'A',
            link: function(scope, element, attrs)
            {
                let value = "";
                let voiceRedirectUrl = attrs.voiceRedirect;
                let manualRedirectUrl = attrs.manualRedirect;

                $(element).on("search", function(ev){
                    value = $(this).val();

                    if($(this).attr("data-by-voice")){
                        window.location.href = voiceRedirectUrl;
                    }
                    else{
                        window.location.href = manualRedirectUrl;
                    }
                });

                $(element).on("keyup", function(ev){
                    let newValue = $(this).val();

                    if(newValue.length > 0 && value.length < newValue.length){
                        $(this).removeAttr("data-by-voice");
                    }
                });
            }
        };
    }]);

})(window.autoparusApp);