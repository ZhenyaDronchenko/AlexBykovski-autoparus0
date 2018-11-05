(function(autoparusApp) {
    'use strict';

    autoparusApp.controller('CarsCtrl', ['$scope', '$http', "ProviderCarsData", function($scope, $http, ProviderCarsData) {
        const DEFAULT_OPTION = "<option value=''>Выбрать</option>";
        const DEFAULT_LABEL = "Выбрать";
        let formSelector = null;
        let url = null;

        function init(formSelectorS, editUrl){
            formSelector = formSelectorS;
            url = editUrl;

            handleForm();
            initAutoSelects();
        }

        function request(url, data, callback) {
            $http({
                method: 'POST',
                headers: {'Content-Type': 'application/x-www-form-urlencoded;charset=utf-8'},
                url: url,
                data: data
            }).then(function (response) {
                callback.call($scope, response);
            }, function (response) {
                console.log("error");
            });
        }

        function handleForm(){
            $(formSelector).ready(function(){
                let formEvents = $.data($(this).get(0), 'events');
                let isExistSubmitHandler = !!(formEvents && formEvents.submit);

                if(!isExistSubmitHandler){
                    $(formSelector).off().on("submit", function(e) {
                        e.preventDefault();

                        sendForm();

                        return false;
                    });
                }

                initPrototype();
            });
        }

        function sendForm() {
            let data = $(formSelector).serialize();

            $(formSelector).find("button[type=submit]").prop("disabled", true);

            request(url, data, function (response) {
                if(response.data.success){

                }

                $("#form-cars-container").html(response.data);
                handleForm();

                $(formSelector).find("button[type=submit]").prop("disabled", false);
            });
        }
        
        function initPrototype() {
            jQuery(document).ready(function() {
                let collectionHolder = $("#cars-container");
                let addCarButton = $("#add-new-car-button");

                collectionHolder.data('index', collectionHolder.find('ul').length);

                addCarButton.click(function(e) {
                    if(collectionHolder.find('ul').length < 5) {
                        addCarForm(collectionHolder);
                    }
                    else{
                        addCarButton.hide();
                    }
                });

                updateRemoveButtons();

                if(!collectionHolder.find('ul').length) {
                    addCarButton.trigger("click");
                }
            });
        }

        function addCarForm(collectionHolder) {
            let prototype = $("#car-prototype-container").html();
            let index = collectionHolder.data('index');
            let newForm = prototype;

            if(collectionHolder.find('ul').length === 4){
                $("#add-new-car-button").hide();
            }

            newForm = newForm.replace(/__index__/g, index);

            collectionHolder.data('index', index + 1);

            collectionHolder.append(newForm);

            updateRemoveButtons();
        }

        function updateRemoveButtons() {
            $(".remove-car-button").on('click', function(e) {
                $(this).parents("ul.car-container").remove();

                if($("#cars-container").find('ul').length < 5) {
                    $("#add-new-car-button").show();
                }
            });
        }

        function initAutoSelects() {
            $("#form-cars-container")
                .on("change", ".car-form-choice-brand", function(){
                    let element = $(this);

                    updateBrand(element.val(), element.parents("ul.car-container"));
                })
                .on("change", ".car-form-choice-model", function(){
                    let element = $(this);

                    updateModel(element.val(), element.parents("ul.car-container"));
                })
                .on("change", ".car-form-choice-engine-type", function(){
                    let element = $(this);
                    let container = element.parents("ul.car-container");

                    updateEngineType(element.val(), container.find(".car-form-choice-model").val(), container);
                })
        }

        function updateBrand(brand, container) {
            ProviderCarsData.getModels(brand).then(function(options){
                let modelsElement = container.find(".car-form-choice-model");
                modelsElement.html("").attr("disabled", false);

                container.find(".car-form-choice-year").html(DEFAULT_OPTION).attr("disabled", true);
                container.find(".car-form-choice-vehicle").html(DEFAULT_OPTION).attr("disabled", true);
                container.find(".car-form-choice-engineType").html(DEFAULT_OPTION).attr("disabled", true);
                container.find(".car-form-choice-capacity").html(DEFAULT_OPTION).attr("disabled", true);

                for(let label in options){
                    modelsElement.append($("<option value='" + options[label] + "'>" + label + "</option>"))
                }
            });
        }

        function updateModel(model, container) {
            ProviderCarsData.getYears(model).then(function(options){
                let keysSorted = Object.keys(options).sort(function(a, b){
                    if(a === DEFAULT_LABEL){
                        return false;
                    }

                    if(b === DEFAULT_LABEL){
                        return true;
                    }

                    return a > b;
                });

                let yearsElement = container.find(".car-form-choice-year");
                yearsElement.html("").attr("disabled", false);

                keysSorted.forEach(function(item){
                    yearsElement.append($("<option value='" + options[item] + "'>" + item + "</option>"))
                });
            });

            ProviderCarsData.getVehicles(model).then(function(options){
                let vehiclesElement = container.find(".car-form-choice-vehicle");
                vehiclesElement.html("").attr("disabled", false);

                for(let label in options){
                    vehiclesElement.append($("<option value='" + options[label] + "'>" + label + "</option>"))
                }
            });

            ProviderCarsData.getEngineTypes(model).then(function(options){
                let engineTypesElement = container.find(".car-form-choice-engine-type");
                engineTypesElement.html("").attr("disabled", false);
                container.find(".car-form-choice-capacity").html(DEFAULT_OPTION).attr("disabled", true);

                for(let label in options){
                    engineTypesElement.append($("<option value='" + options[label] + "'>" + label + "</option>"))
                }
            });
        }

        function updateEngineType(engineType, model, container) {
            ProviderCarsData.getCapacities(model, engineType).then(function(options){
                let capacitiesElement = container.find(".car-form-choice-capacity");
                capacitiesElement.html("").attr("disabled", false);

                for(let label in options){
                    capacitiesElement.append($("<option value='" + options[label] + "'>" + label + "</option>"))
                }
            });
        }

        this.init = init;

    }]);
})(window.autoparusApp);