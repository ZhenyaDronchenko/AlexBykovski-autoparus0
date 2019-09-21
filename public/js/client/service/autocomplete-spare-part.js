(function(autoparusApp) {
    'use strict';

    autoparusApp.service('AutoCompleteResource', function($q, $http){
        let API_URL_SPARE_PARTS = '/search/spare-part?text=';
        let API_URL_SPARE_PARTS_IDS = '/search/spare-part/ids?text=';
        let API_URL_BRAND = '/search/brand?text=';
        let API_URL_MODEL = '/search/model/';
        let API_URL_YEAR = '/search/year/__brand__/__model__/';
        let API_URL_ENGINE_TYPE = '/search/engine-type/__brand__/__model__/';
        let API_URL_ENGINE_CAPACITY = '/search/engine-capacity/__brand__/__model__/__engine_type__';
        let API_URL_VEHICLE_TYPE = '/search/vehicle-type/__brand__/__model__/';

        let API_URL_PHONE_WORK = '/search/phone/work?text=';
        let API_URL_PHONE_BRAND = '/search/phone/brand?text=';
        let API_URL_PHONE_MODEL = '/search/phone/model/';

        this.searchSpareParts = function(term) {
            return search(API_URL_SPARE_PARTS + term);
        };

        this.searchSparePartsIds = function(term) {
            return search(API_URL_SPARE_PARTS_IDS + term);
        };

        this.searchBrands = function(term) {
            return search(API_URL_BRAND + term);
        };

        this.searchModels = function(term, parameters) {
            let url = API_URL_MODEL + parameters.brand + "?text=" + term;

            if(parameters.hasOwnProperty("by-name")){
                url += "&by-name";
            }

            return search(url);
        };

        this.searchYears = function(term, parameters) {
            let url = API_URL_YEAR.replace("__brand__", parameters.brand).replace("__model__", parameters.model) + "?text=" + term;

            if(parameters.hasOwnProperty("by-name")){
                url += "&by-name";
            }

            return search(url);
        };

        this.searchEngineTypes = function(term, parameters) {
            return search(API_URL_ENGINE_TYPE.replace("__brand__", parameters.brand).replace("__model__", parameters.model) + "?text=" + term);
        };

        this.searchEngineCapacities = function(term, parameters) {
            return search(API_URL_ENGINE_CAPACITY.replace("__brand__", parameters.brand).replace("__model__", parameters.model).replace("__engine_type__", parameters.engineType) + "?text=" + term);
        };

        this.searchVehicleTypes = function(term, parameters) {
            return search(API_URL_VEHICLE_TYPE.replace("__brand__", parameters.brand).replace("__model__", parameters.model) + "?text=" + term);
        };

        this.searchPhoneWork = function(term) {
            return search(API_URL_PHONE_WORK + term);
        };

        this.searchPhoneBrands = function(term) {
            return search(API_URL_PHONE_BRAND + term);
        };

        this.searchPhoneModels = function(term, parameters) {
            return search(API_URL_PHONE_MODEL + parameters.brand + "?text=" + term);
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