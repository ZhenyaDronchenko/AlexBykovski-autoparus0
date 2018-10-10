(function(autoparusApp) {
    'use strict';

    autoparusApp.controller('BusinessProfileCtrl', ['$scope', '$http', function($scope, $http) {
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
            });
        }

        function sendForm() {
            let data = $(formSelector).serialize();

            $(formSelector).find("button[type=submit]").prop("disabled", true);

            request(url, data, function (response) {
                if(response.data.success){

                }

                $("#form-business-profile-container").html(response.data);
                handleForm();

                $(formSelector).find("button[type=submit]").prop("disabled", false);
            });
        }

        this.init = init;

    }]);
})(window.autoparusApp);