(function(autoparusApp) {
    'use strict';

    autoparusApp.controller('PersonalDataCtrl', ['$scope', '$http', '$sce', function($scope, $http, $sce) {
        let self = this;
        let formSelector = null;
        let url = null;
        this.formText = "";

        console.log("in controller");

        function init(formSelectorS, editUrl){
            formSelector = formSelectorS;
            url = editUrl;

            sendForm();
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
                $(".phone-profile").mask("+375  (99)  999 - 99 - 99");
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
                console.log(response);
                if(response.data.success){

                }

                self.formText = $sce.trustAsHtml(response.data);
                handleForm();

                $(formSelector).find("button[type=submit]").prop("disabled", false);
            });
        }

        this.init = init;

    }]);
})(window.autoparusApp);