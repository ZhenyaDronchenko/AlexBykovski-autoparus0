(function(autoparusApp) {
    'use strict';

    autoparusApp.service('AutoCompleteResource', function($q, $http){
        let API_URL_SPARE_PARTS = '/search/spare-part?text=';
        let API_URL_BRAND = '/search/brand?text=';

        this.searchSpareParts = function(term) {
            return search(API_URL_SPARE_PARTS + term);
        };

        this.searchBrands = function(term) {
            return search(API_URL_BRAND + term);
        };

        function search(url) {
            let deferred = $q.defer();

            $http.get(url).then(function(suggestions){
                deferred.resolve(suggestions.data);
            }, function() {
                deferred.reject(arguments);
            });

            return deferred.promise;
        }
    });
})(window.autoparusApp);