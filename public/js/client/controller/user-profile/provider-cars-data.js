(function(autoparusApp) {
    'use strict';

    autoparusApp.service('ProviderCarsData', function($q, $http){
        let API_URL_MODELS = '/user-office/get-models-by-brand?brand=[br]';
        let API_URL_YEARS = '/user-office/get-years-by-model?model=[m]';
        let API_URL_VEHICLES = '/user-office/get-vehicles-by-model?model=[m]';
        let API_URL_ENGINE_TYPES = '/user-office/get-engine-types-by-model?model=[m]';
        let API_URL_CAPACITIES = '/user-office/get-capacities-by-model-engine-type?model=[m]&engine_type=[e_t]';

        this.getModels = function(brand) {
            return search(API_URL_MODELS.replace("[br]", brand));
        };

        this.getYears = function(model) {
            return search(API_URL_YEARS.replace("[m]", model));
        };

        this.getVehicles = function(model) {
            return search(API_URL_VEHICLES.replace("[m]", model));
        };

        this.getEngineTypes = function(model) {
            return search(API_URL_ENGINE_TYPES.replace("[m]", model));
        };

        this.getCapacities = function(model, engineType) {
            return search(API_URL_CAPACITIES.replace("[m]", model).replace("[e_t]", engineType));
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