(function(autoparusApp) {
    'use strict';

    autoparusApp.controller('EditGeneralAdvertCtrl', ['$scope', '$http', '$compile', function($scope, $http, $compile) {
        let self = this;
        let formSelector = null;
        let modelSelector = null;
        let sparePartSelector = null;
        let brandSelector = null;
        let url = null;
        this.isCheckedAllModels = null;
        this.isCheckedAllSpareParts = null;

        function init(formSelectorS, modelSelectorS, sparePartSelectorS, brandSelectorS){
            formSelector = formSelectorS;
            modelSelector = modelSelectorS;
            sparePartSelector = sparePartSelectorS;
            brandSelector = brandSelectorS;

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

                        sendForm($(document.activeElement).hasClass("submit-brand-model"), $(document.activeElement));

                        return false;
                    });
                }
            });
        }

        function sendForm(isBrandSubmit, buttonSubmitted) {
            let data = $(formSelector).serialize();
            data = data + '&' + encodeURI(buttonSubmitted.attr('name')) + '=' + encodeURI(buttonSubmitted.attr('value'));

            $(formSelector).find("button[type=submit]").prop("disabled", true);
            url = $(formSelector).attr("action") + "?ajax";

            request(isBrandSubmit ? (url + '&brandSubmit') : url, data, function (response) {

                let el = $compile(response.data)( $scope );

                $("#form-edit-general-advert-container").html("").append(el);
                handleForm();

                $(formSelector).find("button[type=submit]").prop("disabled", false);
            });
        }
        
        function checkAllModels() {
            $(modelSelector).attr("checked", true);
        }

        function uncheckAllModels() {
            $(modelSelector).removeAttr("checked");
        }
        
        function checkAllSpareParts() {
            $(sparePartSelector).attr("checked", true);
        }
        
        function uncheckAllSpareParts() {
            $(sparePartSelector).removeAttr("checked");
        }

        $(document).on("change", modelSelector, function (ev) {
            self.isCheckedAllModels = $(modelSelector).length ===  $(modelSelector + ":checkbox:checked").length;
            $scope.$evalAsync();
        });

        $(document).on("change", sparePartSelector, function (ev) {
            self.isCheckedAllSpareParts = $(sparePartSelector).length ===  $(sparePartSelector + ":checkbox:checked").length;
            $scope.$evalAsync();
        });

        $(document).on("change", brandSelector, function (ev) {
            self.isCheckedAllModels = false;

            $scope.$evalAsync();
        });

        this.init = init;
        this.checkAllModels = checkAllModels;
        this.uncheckAllModels = uncheckAllModels;
        this.checkAllSpareParts = checkAllSpareParts;
        this.uncheckAllSpareParts = uncheckAllSpareParts;

    }]);
})(window.autoparusApp);