(function(autoparusApp) {
    'use strict';

    autoparusApp.controller('OBD2CodeCtrl', ['$scope', '$http', '$compile', function($scope, $http, $compile) {
        let formSelector = null;
        let url = null;
        let redirectUrl = null;

        function init(formSelectorS, editUrl, redirectUrlS){
            formSelector = formSelectorS;
            url = editUrl;
            redirectUrl = redirectUrlS;

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
                    return location.href = redirectUrl.replace("__url_code__", response.data.urlCode)
                }

                let el = $compile(response.data)( $scope );

                $("#form-obd2-code").html("").append(el);
                handleForm();

                $(formSelector).find("button[type=submit]").prop("disabled", false);
            });
        }

        this.init = init;

    }]);
})(window.autoparusApp);