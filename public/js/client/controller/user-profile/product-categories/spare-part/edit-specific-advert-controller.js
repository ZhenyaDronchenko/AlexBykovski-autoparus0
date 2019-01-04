(function(autoparusApp) {
    'use strict';

    autoparusApp.controller('EditSpecificAdvertCtrl', ['$scope', '$http', '$compile', 'ImageUploadService',
        function($scope, $http, $compile, ImageUploadService) {
        let formSelector = null;
        let url = null;
        let submitButtonName = "";
        let fileSelector = "";

        function init(formSelectorS, submitButtonNameS, fileIdS){
            formSelector = formSelectorS;
            submitButtonName = '#' + submitButtonNameS;
            fileSelector = '#' + fileIdS;

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
                }
            });
        }

        function sendForm() {
            let data = $(formSelector).serialize();

            $(formSelector).find("button[type=submit]").prop("disabled", true);
            url = $(formSelector).attr("action");

            request(url, data, function (response) {
                console.log(response);
                let el = $compile(response.data)( $scope );

                $("#form-edit-specific-advert-container").html("").append(el);
                handleForm();

                $(formSelector).find("button[type=submit]").prop("disabled", false);
            });
        }

        this.init = init;

    }]);
})(window.autoparusApp);