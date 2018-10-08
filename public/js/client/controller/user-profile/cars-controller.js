(function(autoparusApp) {
    'use strict';

    autoparusApp.controller('CarsCtrl', ['$scope', '$http', '$sce', function($scope, $http, $sce) {
        let self = this;
        let formSelector = null;
        let url = null;

        function init(formSelectorS, editUrl){
            formSelector = formSelectorS;
            url = editUrl;

            handleForm();
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
                let addTagButton = $("#add-new-car-button");

                collectionHolder.data('index', collectionHolder.find('ul').length);

                addTagButton.click(function(e) {
                    if(collectionHolder.find('ul').length < 5) {
                        addCarForm(collectionHolder);
                    }
                });

                updateRemoveButtons();

                if(!collectionHolder.find('ul').length) {
                    addTagButton.trigger("click");
                }
            });
        }

        function addCarForm(collectionHolder) {
            let prototype = $("#car-prototype-container").html();
            let index = collectionHolder.data('index');
            let newForm = prototype;

            newForm = newForm.replace(/__index__/g, index);

            collectionHolder.data('index', index + 1);

            $("#cars-container").append(newForm);

            updateRemoveButtons();
        }

        function updateRemoveButtons() {
            $(".remove-car-button").on('click', function(e) {
                $(this).parents("ul.moreinfoblock").remove();
            });
        }

        this.init = init;

    }]);
})(window.autoparusApp);