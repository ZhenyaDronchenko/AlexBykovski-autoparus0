(function(autoparusApp) {
    'use strict';

    autoparusApp.controller('CarsCtrl', ['$scope', '$http', '$compile', "ProviderCarsData", function($scope, $http, $compile, ProviderCarsData) {
        const DEFAULT_OPTION = "<option value=''>Выбрать</option>";
        const DEFAULT_EMPTY_OPTION = "<option value=''></option>";
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
                let el = $compile(response.data)( $scope );

                $("#form-cars-container").html("").append(el);
                handleForm();

                $(formSelector).find("button[type=submit]").prop("disabled", false);
            });
        }
        
        function initPrototype() {
            jQuery(document).ready(function() {
                let collectionHolder = $("#cars-container");
                let addCarButton = $("#add-new-car-button");

                collectionHolder.data('index', collectionHolder.find('.car-container').length);

                addCarButton.click(function(e) {
                    if(collectionHolder.find('.car-container').length < 5) {
                        addCarForm(collectionHolder);
                    }
                    else{
                        addCarButton.hide();
                    }
                });

                $("body").on('click', ".remove-car-button", function(e) {
                    $(this).parents(".car-container").remove();

                    if($("#cars-container").find('.car-container').length < 5) {
                        $("#add-new-car-button").show();
                    }
                });

                if(!collectionHolder.find('.car-container').length) {
                    addCarButton.trigger("click");
                }
            });
        }

        function addCarForm(collectionHolder) {
            let prototype = $("#car-prototype-container").html();
            let index = collectionHolder.data('index');
            let newForm = prototype;

            if(collectionHolder.find('.car-container').length === 4){
                $("#add-new-car-button").hide();
            }

            newForm = newForm.replace(/__index__/g, index);

            collectionHolder.data('index', index + 1);

            collectionHolder.append(newForm);
        }

        function initAutoSelects() {
            $("#form-cars-container")
                .on("change", ".car-form-choice-brand", function(){
                    let element = $(this);

                    updateBrand(element.val(), element.parents(".car-container"));
                })
                .on("change", ".car-form-choice-model", function(){
                    let element = $(this);

                    updateModel(element.val(), element.parents(".car-container"));
                })
                .on("change", ".car-form-choice-engine-type", function(){
                    let element = $(this);
                    let container = element.parents(".car-container");

                    updateEngineType(element.val(), container.find(".car-form-choice-model").val(), container);
                })
        }

        function updateBrand(brand, container) {
            ProviderCarsData.getModels(brand).then(function(options){
                let modelsElement = container.find(".car-form-choice-model");
                modelsElement.html("").attr("disabled", false);

                container.find(".car-form-choice-year").html(DEFAULT_EMPTY_OPTION).attr("disabled", true);
                container.find(".car-form-choice-vehicle").html(DEFAULT_OPTION).attr("disabled", true);
                container.find(".car-form-choice-engineType").html(DEFAULT_OPTION).attr("disabled", true);
                container.find(".car-form-choice-capacity").html(DEFAULT_EMPTY_OPTION).attr("disabled", true);
                container.find(".car-form-choice-engine-name").html(DEFAULT_EMPTY_OPTION).attr("disabled", true);
                container.find(".car-form-choice-gear-box-type").html(DEFAULT_OPTION).attr("disabled", true);
                container.find(".car-form-choice-drive-type").html(DEFAULT_OPTION).attr("disabled", true);

                for(let label in options){
                    modelsElement.append($("<option value='" + options[label] + "'>" + label + "</option>"))
                }
            });
        }

        function updateModel(model, container) {
            ProviderCarsData.getEngineTypes(model).then(function(options){
                setEngineTypes(options, container);
            });

            ProviderCarsData.getCarDataByModel(model).then(function(options){
                setYears(options["years"], container);
                setTypesByModel(options["vehicleTypes"], container, ".car-form-choice-vehicle");
                setTypesByModel(options["gearBoxTypes"], container, ".car-form-choice-gear-box-type");
                setTypesByModel(options["driveTypes"], container, ".car-form-choice-drive-type");
            });
        }

        function updateEngineType(engineType, model, container) {
            ProviderCarsData.getCarDataByModelAndEngineType(model, engineType).then(function(options){
                setTypesByModel(options["engineCapacities"], container, ".car-form-choice-capacity");
                setTypesByModel(options["engineNames"], container, ".car-form-choice-engine-name");
            });
        }

        function setYears(options, container) {
            let keysSorted = Object.keys(options).sort(function(a, b){
                if(a === ""){
                    return -1;
                }

                if(b === ""){
                    return 1;
                }

                return a < b ? 1 : -1;
            });

            let yearsElement = container.find(".car-form-choice-year");
            yearsElement.html("").attr("disabled", false);

            keysSorted.forEach(function(item){
                yearsElement.append($("<option value='" + options[item] + "'>" + item + "</option>"))
            });
        }

        function setTypesByModel(options, container, elementSelector) {
            let element = container.find(elementSelector);
            element.html("").attr("disabled", false);

            for(let label in options){
                element.append($("<option value='" + options[label] + "'>" + label + "</option>"))
            }
        }

        function setEngineTypes(options, container) {
            let engineTypesElement = container.find(".car-form-choice-engine-type");
            engineTypesElement.html("").attr("disabled", false);
            container.find(".car-form-choice-capacity").html(DEFAULT_EMPTY_OPTION).attr("disabled", true);
            container.find(".car-form-choice-engine-name").html(DEFAULT_EMPTY_OPTION).attr("disabled", true);

            for(let label in options){
                engineTypesElement.append($("<option value='" + options[label] + "'>" + label + "</option>"))
            }
        }

        this.init = init;

    }]);
})(window.autoparusApp);