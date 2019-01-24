(function(autoparusApp) {
    'use strict';

    autoparusApp.controller('LoginCtrl', ['$scope', '$http', '$window', function($scope, $http, $window) {
        let formSelector = null;
        let url = null;
        let container = null;

        function init(formSelectorS, editUrl, containerS){
            formSelector = formSelectorS;
            url = editUrl;
            container = containerS;

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

                        sendForm('#' + $(this).attr("id"));

                        return false;
                    });
                }
            });
        }

        function sendForm(formSelector) {
            let data = $(formSelector).serialize();
            data = data.replace(/(\_\_modal1\_\_)/g, "").replace(/(\_\_modal2\_\_)/g, "");

            $(formSelector).find("button[type=submit]").prop("disabled", true);

            request(url, data, function (response) {
                if(response.data.success){
                    return $window.location.href = response.data.redirect;
                }

                $(container).html(response.data);

                handleForm();
            });
        }

        this.init = init;

        $("body")
            .on("click", "#close-modal1", function(){
                $("#modal1").removeClass("modal--show");
            })
            .on("click", "#close-modal2", function(){
                $("#modal2").removeClass("modal--show");
            });

    }]);
})(window.autoparusApp);