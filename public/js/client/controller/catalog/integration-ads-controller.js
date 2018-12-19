(function(autoparusApp) {
    'use strict';

    autoparusApp.controller('IntegrationAdsCtrl', ['$http', function($http) {
        const URL = "/integration/get-phone-number/";

        this.initAd = function (key) {
            this["phonesShow_" + key] = false;
            this["phones_" + key] = null;
        };

        this.getPhones = function(url, key) {
            let self = this;

            if(!url || !key){
                this["phones_" + key] = [];

                return false;
            }

            if(this["phones_" + key] === null){
                $http({
                    method: 'POST',
                    url: URL,
                    data: {"url":  url}
                }).then(function (response) {
                    self["phones_" + key] = response.data.phones;
                }, function (response) {
                    console.log("error");
                });
            }

            this["phonesShow_" + key] = !this["phonesShow_" + key];
        };

        this.clickPhone = function(ev){
            if(!($(ev.target).css("color") === "blue")){
                $(ev.target).css("color", "blue");
            }
        };
    }]);
})(window.autoparusApp);