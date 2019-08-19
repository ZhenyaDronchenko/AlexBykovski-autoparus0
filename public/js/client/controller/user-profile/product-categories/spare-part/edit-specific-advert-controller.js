(function(autoparusApp) {
    'use strict';

    autoparusApp.controller('EditSpecificAdvertCtrl', ['$scope', '$http', '$compile', '$rootScope',
        'ImageUploadService',
        function($scope, $http, $compile, $rootScope, ImageUploadService) {
        let formSelector = null;
        let url = null;
        let submitButtonName = "";
        let fileSelector = "";
        let sparePartSelector = "";
        let isDisableSparePart = false;
        let preloaderSelector = "#preloader-view";

        function init(formSelectorS, submitButtonNameS, fileIdS, sparePartId){
            formSelector = formSelectorS;
            submitButtonName = '#' + submitButtonNameS;
            fileSelector = '#' + fileIdS;
            sparePartSelector = sparePartId;

            $rootScope.$on(sparePartSelector + '_select-in-autocomplete', function(event, args) {
                $('#' + sparePartSelector).attr("disabled", true);
                isDisableSparePart = true;
            });

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

                        $(preloaderSelector).show();
                        $(submitButtonName).val($(document.activeElement).attr('data-name'));

                        sendForm();

                        return false;
                    });

                    $("#initiator-upload-image").click(function(e) {
                        $("#upload-image-input").trigger("click");
                    });

                    $("#upload-image-input").change(function(e){
                        let file = e.target.files[0];

                        ImageUploadService.processUploadImage(file, function(fileCompressed){
                            addImagePreview(fileCompressed, function(viewImage){
                                $(fileSelector).val(viewImage);
                                $("#preview-image").attr("src", viewImage);
                            });
                        })
                    });

                    $("#clear-spare-part").click(function(e){
                        $('#' + sparePartSelector).attr("disabled", false).val("");
                        isDisableSparePart = false;
                    });

                    if(isDisableSparePart){
                        $('#' + sparePartSelector).attr("disabled", true);
                    }
                }
            });
        }

        function sendForm() {
            $('#' + sparePartSelector).attr("disabled", false);
            let data = $(formSelector).serialize();

            $(formSelector).find("button[type=submit]").prop("disabled", true);
            url = $(formSelector).attr("action");

            request(url, data, function (response) {
                $(preloaderSelector).hide();
                let el = $compile(response.data)( $scope );

                $("#form-edit-specific-advert-container").html("").append(el);
                handleForm();

                $(formSelector).find("button[type=submit]").prop("disabled", false);
            });
        }

        this.init = init;

    }]);
})(window.autoparusApp);