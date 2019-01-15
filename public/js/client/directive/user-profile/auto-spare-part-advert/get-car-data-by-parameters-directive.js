(function(autoparusApp) {
    'use strict';

    autoparusApp.directive("getCarDataByParameters",['ProviderCarsData', 'SetCarDataService',
        function(ProviderCarsData, SetCarDataService){
        return{
            restrict: 'A',
            link: function(scope, element, attrs)
            {
                let brandId = '#' + attrs.brandId;
                let modelId = '#' + attrs.modelId;
                let yearId = '#' + attrs.yearId;
                let engineTypeId = '#' + attrs.engineTypeId;
                let engineCapacityId = '#' + attrs.engineCapacityId;
                let engineNameId = '#' + attrs.engineNameId;
                let gearBoxTypeId = '#' + attrs.gearBoxTypeId;
                let vehicleTypeId = '#' + attrs.vehicleTypeId;
                let driveTypeId = '#' + attrs.driveTypeId;
                let body = $("body");

                let optionTemplate = "<option value='__value__'>__label__</option>";
                let optionDefault1 = "<option value=''>Выбрать</option>";
                let optionDefault2 = "<option value=''></option>";

                body.on("change", brandId, function(ev){
                    ProviderCarsData.getModels($(this).val()).then(function(models){
                        $(yearId).html(optionDefault2);
                        $(engineTypeId).html(optionDefault1);
                        $(engineCapacityId).html(optionDefault2);
                        $(engineNameId).html(optionDefault2);
                        $(gearBoxTypeId).html(optionDefault1);
                        $(vehicleTypeId).html(optionDefault1);
                        $(driveTypeId).html(optionDefault1);

                        SetCarDataService.setModels(models, modelId, optionTemplate);
                    });
                });

                body.on("change", modelId, function(ev) {
                    ProviderCarsData.getCarDataByModel($(this).val()).then(function(data){
                        $(engineCapacityId).html(optionDefault2);
                        $(engineNameId).html(optionDefault2);

                        SetCarDataService.setYears(data["years"], yearId, optionTemplate);
                        SetCarDataService.setEngineTypes(data["engineTypes"], engineTypeId, optionTemplate);
                        SetCarDataService.setGearBoxTypes(data["gearBoxTypes"], gearBoxTypeId, optionTemplate);
                        SetCarDataService.setVehicleTypes(data["vehicleTypes"], vehicleTypeId, optionTemplate);
                        SetCarDataService.setDriveTypes(data["driveTypes"], driveTypeId, optionTemplate);
                    });
                });

                body.on("change", engineTypeId, function(ev) {
                    ProviderCarsData.getCarDataByModelAndEngineType($(modelId).val(), $(this).val()).then(function(data){
                        $(engineCapacityId).html(optionDefault2);
                        $(engineNameId).html(optionDefault2);

                        SetCarDataService.setEngineCapacities(data["engineCapacities"], engineCapacityId, optionTemplate);
                        SetCarDataService.setEngineNames(data["engineNames"], engineNameId, optionTemplate, data["countEngineNames"]);
                    });
                });

                body.on("change", engineCapacityId, function(ev) {
                    ProviderCarsData.getCarDataByModelAndEngineTypeAndCapacity($(modelId).val(), $(engineTypeId).val(), $(this).val()).then(function(data){
                        SetCarDataService.setEngineNames(data["engineNames"], engineNameId, optionTemplate, data["countEngineNames"]);
                    });
                });
            }
        };
    }]);

})(window.autoparusApp);