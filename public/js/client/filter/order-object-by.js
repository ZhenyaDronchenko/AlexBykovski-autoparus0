(function(autoparusApp) {
    'use strict';

    autoparusApp.filter('orderObjectBy', function() {
        return function(items, field, reverse) {
            let  filtered = [];

            angular.forEach(items, function(item) {
                filtered.push(item);
            });

            filtered.sort(function (a, b) {
                return (a[field] > b[field] ? 1 : -1);
            });

            if(reverse){
                filtered.reverse();
            }

            return filtered;
        };
    });
})(window.autoparusApp);
