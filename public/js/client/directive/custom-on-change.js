(function(autoparusApp) {
    'use strict';

    autoparusApp.directive('customOnChange', function() {
        return {
            restrict: 'A',
            link: function (scope, element, attrs) {
                let onChangeHandler = scope.$eval(attrs.customOnChange);

                element.on('change', onChangeHandler);
                element.on('$destroy', function() {
                    element.off();
                });

            }
        };
    });

})(window.autoparusApp);