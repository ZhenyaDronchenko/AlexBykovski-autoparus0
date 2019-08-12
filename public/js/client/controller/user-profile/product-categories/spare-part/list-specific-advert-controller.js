(function(autoparusApp) {
    'use strict';

    autoparusApp.controller('ListSpecificAdvertCtrl', ['$http', function($http) {
        let self = this;

        const URL_GET_MODELS = "/user-office/product-categories/spare-part/ajax/models-by-brand/";
        const URL_GET_ADVERTS = "/user-office/product-categories/spare-part/ajax/specific-adverts-by-parameters";
        const URL_CHANGE_ACTIVITY = "/user-office/product-categories/spare-part/ajax/change-specific-advert-activity/";

        this.adverts = [];
        this.models = [];
        this.brand = '';
        this.model = '';
        this.sparePart = '';
        this.page = 1;
        this.countPages = 1;

        function init() {
            changeFilter();
        }

        function changeFilter(changeType){
            if(changeType === 'brand'){
                updateModels();
            }

            if(changeType !== "page"){
                self.page = 1;
            }

            $http({
                method: 'POST',
                url: URL_GET_ADVERTS,
                data: {
                    "brand" : self.brand,
                    "model" : self.model,
                    "sparePart" : self.sparePart,
                    "page" : self.page,
                },
            }).then(function (response) {
                self.adverts = response.data["adverts"];
                self.countPages = response.data["countPages"];
            }, function (response) {
                console.log("error");
            });
        }

        function updateModels() {
            if(!self.brand){
                return self.models = [];
            }

            $http.get(URL_GET_MODELS + self.brand).then(function(response){
                self.models = response.data;
            }, function(error) {
                console.error(error);
            });
        }

        function getNumberForPaginationCell(cellIndex){
            switch (cellIndex){
                case 1:
                    if(self.page < 5 || self.countPages < 8){
                        return 1;
                    }
                    if(self.countPages - self.page < 4){
                        return self.countPages - 6;
                    }

                    return self.page - 3;
                case 2:
                    if(self.page < 5 || self.countPages < 8){
                        return 2;
                    }
                    if(self.countPages - self.page < 4){
                        return self.countPages - 5;
                    }

                    return self.page - 2;
                case 3:
                    if(self.page < 5 || self.countPages < 8){
                        return 3;
                    }
                    if(self.countPages - self.page < 4){
                        return self.countPages - 4;
                    }

                    return self.page - 1;
                case 4:
                    if(self.page < 5 || self.countPages < 8){
                        return 4;
                    }
                    if(self.countPages - self.page < 4){
                        return self.countPages - 3;
                    }

                    return self.page;
                case 5:
                    if(self.page < 5 || self.countPages < 8){
                        return 5;
                    }
                    if(self.countPages - self.page < 4){
                        return self.countPages - 2;
                    }

                    return self.page + 1;
                case 6:
                    if(self.page < 5 || self.countPages < 8){
                        return 6;
                    }
                    if(self.countPages - self.page < 4){
                        return self.countPages - 1;
                    }

                    return self.page + 2;
                case 7:
                    if(self.page < 5 || self.countPages < 8){
                        return 7;
                    }
                    if(self.countPages - self.page < 4){
                        return self.countPages;
                    }

                    return self.page + 3;
                default:
                    return 0;
            }
        }

        function clickPaginationCell(cellIndex) {
            let number = getNumberForPaginationCell(cellIndex);

            if(number === self.page){
                return false;
            }

            self.page = number;

            changeFilter('page');
        }

        function isActiveCell(cellIndex){
            return self.page === getNumberForPaginationCell(cellIndex);
        }

        function getTechnicalDetailAdvert(advert) {
            //2000г, Бензин, 2.5/7AZTE, механика, универсал, передний привод, БУ, Вналичии
            let description = "";

            if(advert.year){
                description += advert.year + 'r, ';
            }

            if(advert.engineType){
                description += advert.engineType + ', ';
            }

            if(advert.engineCapacity || advert.engineName){
                if(advert.engineCapacity && advert.engineName){
                    description += advert.engineCapacity + '/' + advert.engineName + ', ';
                }
                else{
                    if(advert.engineCapacity){
                        description += advert.engineCapacity + ', ';
                    }
                    else{
                        description += advert.engineName + ', ';
                    }
                }
            }

            if(advert.gearBoxType){
                description += advert.gearBoxType + ', ';
            }

            if(advert.vehicleType){
                description += advert.vehicleType + ', ';
            }

            if(advert.driveType){
                description += advert.driveType + ', ';
            }

            if(advert.condition){
                description += advert.condition + ', ';
            }

            if(advert.stockType){
                description += advert.stockType + ', ';
            }

            if(description.length > 2 && description.substring(description.length - 2) === ', '){
                description = description.substring(0, description.length - 2);
            }

            return description;
        }

        function changeAdvertActivity(advertIndex) {
            $http({
                method: 'POST',
                url: URL_CHANGE_ACTIVITY + self.adverts[advertIndex]["id"],
            }).then(function (response) {
                self.adverts[advertIndex]["isActive"] = response.data["isActive"];
                self.adverts[advertIndex]["activatedAt"] = response.data["date"];
            }, function (response) {
                console.log("error");
            });
        }

        function linkShowAdvert(advert) {
            return Routing.generate("show_brand_catalog_in_stock", {
                "urlBrand" : advert.brandUrl,
                "urlModel" : advert.modelUrl,
                "urlSP" : advert.sparePartUrl,
                "urlCity" : advert.cityUrl,
            })
        }

        this.init = init;
        this.changeFilter = changeFilter;
        this.getNumberForPaginationCell = getNumberForPaginationCell;
        this.clickPaginationCell = clickPaginationCell;
        this.isActiveCell = isActiveCell;
        this.getTechnicalDetailAdvert = getTechnicalDetailAdvert;
        this.changeAdvertActivity = changeAdvertActivity;
        this.linkShowAdvert = linkShowAdvert;

    }]);
})(window.autoparusApp);