(function(autoparusApp) {
    'use strict';

    autoparusApp.service('AutoCompleteResource', function($q, $http){
        let API_URL_SPARE_PARTS = '/search/spare-part?text=';

        this.searchSpareParts = function(term) {
            let deferred = $q.defer();

            $http.get(API_URL_SPARE_PARTS + term).then(function(flights){
                deferred.resolve(flights.data);
            }, function() {
                deferred.reject(arguments);
            });

            return deferred.promise;
        }
    });
})(window.autoparusApp);