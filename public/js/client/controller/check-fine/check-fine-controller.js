(function(autoparusApp) {
    'use strict';

    autoparusApp.controller('CheckFineCtrl', ['$http', '$scope', '$compile', function($http, $scope, $compile) {
        const REQUIRED_FIELD = "Поле обязательное для заполнения";
        const REQUIRED_FIELDS = "Поля обязательные для заполнения";
        const ERROR_FIO = "Только русские буквы и \"-\"";
        const ERROR_SERIES = "Серия - только 3 русские буквы";
        const ERROR_NUMBER = "Номер - только 6 цифр";
        const ERROR_PHONE = "Некорректный номер телефона";
        const ERROR_EMAIL = "Некорректный email";

        let self = this;
        let formSelector = null;
        let formContainer = null;
        let url = null;

        this.form = {
            lastName : {
                value : "",
                error : "",
            },
            firstName : {
                value : "",
                error : "",
            },
            patronymic : {
                value : "",
                error : "",
            },
            series : {
                value : "",
                error : "",
            },
            number : {
                value : "",
                error : "",
            },
            phone : {
                value : "",
                error : "",
            },
            email : {
                value : "",
                error : "",
            }
        };

        function init(formSelectorS, editUrl, formContainerS){
            formSelector = formSelectorS;
            url = editUrl;
            formContainer = formContainerS;

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
                $(".phone-mask").mask("+375  (99)  999 - 99 - 99");
                let formEvents = $.data($(this).get(0), 'events');
                let isExistSubmitHandler = !!(formEvents && formEvents.submit);

                if(!isExistSubmitHandler){
                    $(formSelector).off().on("submit", function(e) {
                        e.preventDefault();

                        if(!checkFormValidation()){
                            return false;
                        }

                        $scope.$evalAsync();

                        sendForm();

                        return false;
                    });
                }
            });
        }

        function sendForm() {
            let data = $(formSelector).serialize();

            $(formSelector).find("button[type=submit]").prop("disabled", true);
            $("#check-fine-message").show();

            request(url, data, function (response) {
                $("#check-fine-message").hide();

                if(response.data.success){
                    let checkResult = response.data.checkResult;
                    checkResult = checkResult !== "false" ? checkResult : "База данных ГАИ не отвечает. Попробуйте ещё раз немного позже";

                    $("#result-check-fine").html(checkResult);
                    $("#check-fine-result-modal").addClass("modal--show");
                    $(formSelector).find("button[type=submit]").prop("disabled", false);

                    return true;
                }

                let el = $compile(response.data)( $scope );


                $(formContainer).html("").append(el);
                handleForm();

                $(formSelector).find("button[type=submit]").prop("disabled", false);
            });
        }

        function checkFormValidation() {
            self.form.phone.value = $("#potential_user_check_fine_phone").val();

            const isValidLastName = checkFIOElement("lastName");
            const isValidFirstName = checkFIOElement("firstName");
            const isValidPatronymic = checkFIOElement("patronymic");
            const isValidSeriesNumber = checkSeriesNumber();
            const isValidPhone = checkPhone();
            const isValidEmail= checkEmail();

            return isValidLastName && isValidFirstName && isValidPatronymic && isValidSeriesNumber && isValidPhone &&
                isValidEmail;
        }

        function checkFIOElement(field) {
            let value = self.form[field].value;

            if(!value){
                self.form[field].error = REQUIRED_FIELD;

                return false;
            }

            if(!(/^[а-яА-ЯЁё\-]+$/.test(value))){
                self.form[field].error = ERROR_FIO;

                return false;
            }

            self.form[field].error = "";

            return true;
        }

        function checkSeriesNumber() {
            self.form.series.value = self.form.series.value.toUpperCase();
            $("#potential_user_check_fine_series").val(self.form.series.value);

            let series = self.form.series.value;
            let number = self.form.number.value;

            if(!series || !number){
                self.form.series.error = REQUIRED_FIELDS;

                return false;
            }

            let isValidSeries = /^[А-ЯЁ]{3}$/.test(series);
            let isValidNumber = /^\d{6}$/.test(number);

            if(!isValidSeries){
                self.form.series.error = ERROR_SERIES;
            }
            else{
                self.form.series.error = "";
            }

            if(!isValidNumber){
                self.form.number.error = ERROR_NUMBER;
            }
            else{
                self.form.number.error = "";
            }

            return isValidSeries && isValidNumber;
        }

        function checkPhone() {
            let value = self.form.phone.value.replace(/ /g, '');

            if(!value){
                self.form.phone.error = REQUIRED_FIELD;

                return false;
            }

            if(!(/^\+375\(\d{2}\)\d{3}\-\d{2}\-\d{2}$/.test(value))){
                self.form.phone.error = ERROR_PHONE;

                return false;
            }

            self.form.phone.error = "";

            return true;
        }

        function checkEmail() {
            let value = self.form.email.value;
            let regex = /^(([^<>()\[\]\\.,;:\s@"]+(\.[^<>()\[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;

            if(!value){
                self.form.email.error = REQUIRED_FIELD;

                return false;
            }

            if(!(regex.test(value.toLowerCase()))){
                self.form.email.error = ERROR_EMAIL;

                return false;
            }

            self.form.email.error = "";

            return true;
        }

        this.init = init;
    }]);
})(window.autoparusApp);