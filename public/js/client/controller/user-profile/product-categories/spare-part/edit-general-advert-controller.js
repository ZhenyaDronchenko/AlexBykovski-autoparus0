(function(autoparusApp) {
    'use strict';

    autoparusApp.controller('EditGeneralAdvertCtrl', ['$scope', '$http', '$compile', function($scope, $http, $compile) {
        let formSelector = null;
        let url = null;

        function init(formSelectorS, editUrl){
            formSelector = formSelectorS;

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

                        sendForm($(document.activeElement).hasClass("submit-brand-model"));

                        return false;
                    });
                }
            });
        }

        function sendForm(isBrandSubmit) {
            let data = $(formSelector).serialize();

            $(formSelector).find("button[type=submit]").prop("disabled", true);
            url = $(formSelector).attr("action") + "?ajax";

            request(isBrandSubmit ? (url + '&brandSubmit') : url, data, function (response) {

                let el = $compile(response.data)( $scope );

                $("#form-edit-general-advert-container").html("").append(el);
                handleForm();

                $(formSelector).find("button[type=submit]").prop("disabled", false);
            });
        }

        this.init = init;

    }]);
})(window.autoparusApp);