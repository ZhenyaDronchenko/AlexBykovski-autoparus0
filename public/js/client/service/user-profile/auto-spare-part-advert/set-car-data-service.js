(function(autoparusApp) {
    'use strict';

    autoparusApp.service('SetCarDataService', function(){
        let self = this;

        this.setModels = function(models, modelId, optionTemplate) {
            setDataWithPlaceholder(models, modelId, optionTemplate);
        };

        this.setYears = function(years, yearId, optionTemplate) {
            setDataWithEmptyPlaceholder(years, yearId, optionTemplate);
        };

        this.setEngineTypes = function(engineTypes, engineTypeId, optionTemplate) {
            setDataWithPlaceholder(engineTypes, engineTypeId, optionTemplate);
        };

        this.setGearBoxTypes = function(gearBoxTypes, gearBoxTypeId, optionTemplate) {
            setDataWithPlaceholder(gearBoxTypes, gearBoxTypeId, optionTemplate);
        };

        this.setVehicleTypes = function(vehicleTypes, vehicleTypeId, optionTemplate) {
            setDataWithPlaceholder(vehicleTypes, vehicleTypeId, optionTemplate);
        };

        this.setDriveTypes = function(driveTypes, driveTypeId, optionTemplate) {
            setDataWithPlaceholder(driveTypes, driveTypeId, optionTemplate);
        };

        this.setEngineCapacities = function(engineCapacities, engineCapacityId, optionTemplate) {
            setDataWithEmptyPlaceholder(engineCapacities, engineCapacityId, optionTemplate);
        };

        this.setEngineNames = function(engineNames, engineNameId, optionTemplate, isSetDefault) {
            setDataWithEmptyPlaceholder(engineNames, engineNameId, optionTemplate, isSetDefault);
        };

        function setDataWithPlaceholder(items, selector, optionTemplate) {
            $(selector).html("");
            let itemActive = null;
            let length = Object.keys(items).length;

            for(let name in items){
                if(length === 2 && items[name]){
                    itemActive = items[name];
                }

                let itemOption = $(optionTemplate);
                itemOption.val(items[name]);
                itemOption.html(name);

                $(selector).append(itemOption);
            }

            if(itemActive){
                $(selector).val(itemActive).trigger("change");
            }
        }

        function setDataWithEmptyPlaceholder(items, selector, optionTemplate, isSetDefault) {
            if(isSetDefault == null){
                isSetDefault = true;
            }

            $(selector).html("");
            let itemActive = null;
            let length = Object.keys(items).length;
            let itemsOptions = [];

            for(let name in items){
                if(length === 2 && items[name]){
                    itemActive = items[name];
                }

                let itemOption = $(optionTemplate);
                itemOption.val(items[name]);
                itemOption.html(name);

                if(!name){
                    itemsOptions.unshift(itemOption);
                }
                else {
                    itemsOptions.push(itemOption);
                }
            }

            for(let index in itemsOptions){
                $(selector).append(itemsOptions[index]);
            }

            if(itemActive && isSetDefault){
                $(selector).val(itemActive).trigger("change");
            }
        }
    });
})(window.autoparusApp);