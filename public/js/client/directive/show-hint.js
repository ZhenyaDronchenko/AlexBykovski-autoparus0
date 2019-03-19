(function (autoparusApp) {
    'use strict';

    autoparusApp.directive("showHint", [function () {
        return {
            restrict: 'A',
            scope: true,
            link: function (scope, element, attrs) {
                let triggerOpen = attrs.triggerOpen;
                let triggerClose = attrs.triggerClose;

                if (triggerOpen) {
                    $("body").on("click", triggerOpen, function (ev) {
                        $(element).show();
                    });
                }

                if (triggerClose) {
                    $("body").on("click", triggerClose, function (ev) {
                        $(element).hide();
                    });
                }
            }
        };
    }]);

})(window.autoparusApp);