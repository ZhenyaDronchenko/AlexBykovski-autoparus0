(function(autoparusApp) {
    'use strict';

    autoparusApp.directive("searchField",[function(){
        return{
            restrict: 'A',
            scope: true,
            link: function(scope, element, attrs)
            {
                let searchButton = $(attrs.searchButton);

                $(element).keypress(function (ev) {
                    if(ev.which === 13)  // the enter key code
                    {
                        searchButton.click();
                        return false;
                    }
                });
            }
        };
    }]);

})(window.autoparusApp);