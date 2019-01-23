(function(autoparusApp) {
    'use strict';

    autoparusApp.controller('RegistrationCtrl', ['$scope', '$http', '$window', '$compile', '$cookies',
        function($scope, $http, $window, $compile, $cookies) {
        const FIELDS_COOKIE_SELECTOR = ".save-field";

        let formSelector = null;
        let url = null;
        let checkEmailEndUrl = null;
        let modal = $(".overlay");
        let closeModal = modal.find(".modal-close");
        let nameCookieKey = null;
        let emailCookieKey = null;
        let phoneCookieKey = null;
        let termsCookieKey = null;
        let allCookieKey = null;

        function init(formSelectorS, editUrl, checkEmailEndUrlS,
                      nameCookieKeyS, emailCookieKeyS, phoneCookieKeyS, termsCookieKeyS, allCookieKeyS){
            formSelector = formSelectorS;
            url = editUrl;
            checkEmailEndUrl = checkEmailEndUrlS;
            nameCookieKey = nameCookieKeyS;
            emailCookieKey = emailCookieKeyS;
            phoneCookieKey = phoneCookieKeyS;
            termsCookieKey = termsCookieKeyS;
            allCookieKey = allCookieKeyS;

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
                $(".phone-registration").mask("+375  (99)  999 - 99 - 99");

                if($(formSelector).hasClass("valid-form") && modal.length && !modal.hasClass("modal--show")){
                    checkEmailEnd($(".registration-email-field").val());
                    modal.addClass("modal--show");

                    $cookies.remove(allCookieKey);
                }

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
            let modal = $(".overlay");

            $(formSelector).find("button[type=submit]").prop("disabled", true);

            request(url, data, function (response) {
                let el = $compile(response.data)( $scope );

                $("#form-registration-container").html("").append(el);

                handleForm();

                $(formSelector).find("button[type=submit]").prop("disabled", false);
            });
        }

        function checkEmailEnd(email)
        {
            request(checkEmailEndUrl, {"email" : email}, function (response) {
                if(response && response.data && response.data.exist){
                    $("#link-to-mail").attr("href", response.data.domain).show();
                }
            });
        }

        closeModal.click(function(ev){
            $window.location.href = '/';
        });

        $("body").on("change input", FIELDS_COOKIE_SELECTOR, function(event){
            let newValues = {};
            newValues[nameCookieKey] = $(FIELDS_COOKIE_SELECTOR + '.' + nameCookieKey).val();
            newValues[emailCookieKey] = $(FIELDS_COOKIE_SELECTOR + '.' + emailCookieKey).val();
            newValues[phoneCookieKey] = $(FIELDS_COOKIE_SELECTOR + '.' + phoneCookieKey).val();
            newValues[termsCookieKey] = $(FIELDS_COOKIE_SELECTOR + '.' + termsCookieKey).is(":checked");

            $cookies.putObject(allCookieKey, newValues);
        });

        this.init = init;

    }]);
})(window.autoparusApp);