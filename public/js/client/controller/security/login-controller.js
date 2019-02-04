(function(autoparusApp) {
    'use strict';

    autoparusApp.controller('LoginCtrl', ['$scope', '$http', '$window', '$compile', function($scope, $http, $window, $compile) {
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

                $(".type-username[type=tel]").mask("+375  (99)  999 - 99 - 99");

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

                let el = $compile(response.data)( $scope );

                $(container).html("").append(el);

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
            })
            .on("click", ".change-type-username", function(){
                let buttons = $(".change-type-username");
                let userNames = $(".type-username");
                let changeButtonValue = buttons.attr("data-change-value");
                let changeType = userNames.attr("data-change-type");
                let changePlaceholder = userNames.attr("data-change-placeholder");

                buttons.attr("data-change-value", buttons.html());
                buttons.html(changeButtonValue);

                userNames.attr("data-change-type", userNames.attr("type"));
                userNames.attr("data-change-placeholder", userNames.attr("placeholder"));
                userNames.attr("type", changeType);
                userNames.attr("placeholder", changePlaceholder);

                if(changeType === "tel"){
                    userNames.mask("+375  (99)  999 - 99 - 99");
                }
                else{
                    userNames.unmask();
                }

                userNames.val("");
            })
        ;

    }]);
})(window.autoparusApp);