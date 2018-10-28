(function(autoparusApp) {
    'use strict';

    autoparusApp.directive("userUploadLink",[function(){
        return{
            restrict: 'A',
            link: function(scope, element, attrs)
            {
                let link = $(attrs.linkSelector);
                let input = $(attrs.inputSelector);
                let method = $(attrs.actionMethod);
                let fileName = "";

                link.click(function(e){
                    input.trigger("click");
                });

                let dialogContentSize = window.innerWidth > window.innerHeight ? window.innerHeight * 0.9 : window.innerWidth * 0.9;
                let cropperContentSize = window.innerWidth > window.innerHeight ? window.innerHeight * 0.8 : window.innerWidth * 0.8;

                $("#dialog-cropper-container").width(dialogContentSize);
                $("#dialog-cropper-container").height(dialogContentSize);
                $("#image-preview-container").width(cropperContentSize);
                $("#image-preview-container").height(cropperContentSize);

                input.change(function(e){
                    let previewImage = $("#image-preview-container img");
                    fileName = e.target.files[0].name;

                    compress(e.target.files[0], function(file){
                        workAfterCompress(file);
                    });

                    function workAfterCompress(file) {
                        addImagePreview(file, function(data){
                            previewImage.attr("src", data);

                            addDialog();
                        });
                    }

                    function addDialog() {
                        $( "#dialog-cropper-container" ).dialog({
                            modal: true,
                            closeOnEscape: false,
                            open: function(event, ui) {
                                $(".ui-dialog-titlebar-close", ui.dialog | ui).hide();
                                addCropper();
                            },
                            width: dialogContentSize,
                            height: dialogContentSize,
                            buttons: {
                                "Отменить": function() {
                                    $( this ).dialog( "close" );
                                },
                                "Сохранить": function() {
                                    processCroppedImage(this);
                                }
                            }
                        });
                    }

                    function addCropper() {
                        previewImage.cropper("destroy");

                        previewImage.cropper({
                            aspectRatio: 3 / 2,
                        });
                    }

                    function processCroppedImage(dialogInstance) {
                        let imageData = previewImage.cropper("getCroppedCanvas");
                        imageData.toBlob((blob) => {
                            const formData = new FormData();

                            formData.append('file', blob, (new Date()).getTime() + fileName);

                            $.ajax('/user-office/upload-user-photo-ajax', {
                                method: "POST",
                                data: formData,
                                processData: false,
                                contentType: false,
                                success(data) {
                                    if(data.success) {
                                        $("#user-photo").attr("src", data.path);
                                    }

                                    $( dialogInstance ).dialog( "close" );
                                },
                                error(data) {
                                    console.error('Upload error');
                                },
                            });
                        });
                    }
                });
            }
        };
    }]);

})(window.autoparusApp);