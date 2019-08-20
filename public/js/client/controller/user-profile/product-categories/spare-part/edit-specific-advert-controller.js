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

        let dialogContentSize = window.screen.availWidth > window.screen.availHeight ? window.screen.availHeight : window.screen.availWidth;
        let cropperContentSize = dialogContentSize * 0.6;
        let previewImage = $("#image-preview-container img");
        let cropperContainer = $("#dialog-cropper-container");

        const IMAGE_SIZES = [540, 360];

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
                        let fileName = file.name;

                        ImageUploadService.init(cropperContentSize, previewImage, cropperContainer, dialogContentSize,
                            $(this), fileName, null, null, function(formData){
                                addImagePreview(formData.get("file"), function (dataImage) {
                                    cropperContainer.removeClass("modal--show");
                                    $(this).val('');

                                    $(fileSelector).val(dataImage);
                                    $("#preview-image").attr("src", dataImage);
                                });
                            }, IMAGE_SIZES);

                        // ImageUploadService.processUploadImage(file, function(fileCompressed){
                        //     addImagePreview(fileCompressed, function(viewImage){
                        //         $(fileSelector).val(viewImage);
                        //         $("#preview-image").attr("src", viewImage);
                        //     });
                        // })

                        ImageUploadService.processUploadImage(file);
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