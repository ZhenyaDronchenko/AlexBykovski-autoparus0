(function(autoparusApp) {
    'use strict';

    autoparusApp.controller('IntegrationAdsCtrl', ['$http', function($http) {
        const URL = "/integration/get-phone-number/";
        this.showSuggestions = false;
        this.showAbsent = false;
        this.adverts = [];
        this.phonesShow = {};
        this.phones = {};
        let self = this;

        this.init = function (showSuggestions, adverts) {
            this.showSuggestions = showSuggestions;
            this.adverts = angular.fromJson(adverts);

            initAdverts();
        };

        this.getPhones = function(url, key) {
            let self = this;

            if(!url || !key && key !== 0){
                this.phones[key] = [];

                return false;
            }

            if(this.phones[key] === null){
                $http({
                    method: 'POST',
                    url: URL,
                    data: {"url":  url}
                }).then(function (response) {
                    self.phones[key] = response.data.phones;
                }, function (response) {
                    console.log("error");
                });
            }

            this.phonesShow[key] = !this.phonesShow[key];
        };

        this.clickPhone = function(ev){
            if(!($(ev.target).css("color") === "blue")){
                $(ev.target).css("color", "blue");
            }
        };

        this.loadAdverts = function(params) {
            let parsedParams = angular.fromJson(params);
            let url = getUrlProvideAdverts(parsedParams);

            if(!url || !parsedParams){
                return false;
            }

            $http({
                method: 'POST',
                url: url,
                data: parsedParams
            }).then(function (response) {
                self.adverts = response.data;
                initAdverts();
                self.showSuggestions = true;

                if(!self.adverts.length){
                    self.showAbsent = true;
                }

            }, function (response) {
                console.log("error");
            });
        };

        function getUrlProvideAdverts(params) {
            params["ajax"] = "ajax";

            if(params.hasOwnProperty("urlCity")){
                return Routing.generate('show_suggestions_brand_model_spare_part_with_city', params);
            }

            return Routing.generate('show_suggestions_brand_model_spare_part', params);
        }

        function initAdverts () {
            for(let key in self.adverts){
                self.phonesShow[key] = false;
                self.phones[key] = null;
            }
        }
    }]);
})(window.autoparusApp);