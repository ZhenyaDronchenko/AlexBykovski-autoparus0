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
                let jCropApi = null;

                link.click(function(e){
                    input.trigger("click");
                });

                let dialogContentSize = window.innerWidth > window.innerHeight ? window.innerHeight : window.innerWidth;
                let cropperContentSize = dialogContentSize;

                $("#dialog-cropper-container").width(dialogContentSize);
                $("#dialog-cropper-container").height(dialogContentSize);

                input.change(function(e){
                    let previewImage = $("#image-preview-container img");
                    fileName = e.target.files[0].name;

                    compress(e.target.files[0], [cropperContentSize], function(file){
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

                                    input.val('');
                                },
                                "Сохранить": function() {
                                    processCroppedImage(this);

                                    input.val('');
                                }
                            }
                        });
                    }

                    function addCropper() {
                        let JcropAPI = previewImage.data('Jcrop');

                        if(JcropAPI) {
                            JcropAPI.destroy();
                        }

                        getImageData(previewImage.attr("src"), function(data){
                            const trueSize = getJCropTrueSize(data["width"], data["height"], cropperContentSize);
                            const trueWidth = trueSize[0];
                            const trueHeight = trueSize[1];
                            $("#dialog-cropper-container").width(trueWidth);
                            $("#dialog-cropper-container").height(trueHeight);

                            previewImage.Jcrop({
                                aspectRatio: 3 / 2,
                                maxSize: [cropperContentSize, cropperContentSize],
                                boxWidth: cropperContentSize,
                                boxHeight: cropperContentSize,
                                setSelect:   getJCropDefaultSelected(trueWidth, trueHeight),
                            }, function () { jCropApi = this; });
                        });
                    }

                    function processCroppedImage(dialogInstance) {
                        getImageByCoordinatesFromImage(previewImage.attr("src"), jCropApi.ui.selection.last, true, function(file){
                            const formData = new FormData();

                            formData.append('file', file, (new Date()).getTime() + fileName);

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

                    function getJCropDefaultSelected(width, height) {
                        const baseSide = width > height ? height : width;
                        const selectedH = baseSide / 9;
                        const selectedW = baseSide / 6;

                        const x = width/3 - selectedW/3;
                        const y = height/3 - selectedH/3;
                        const x1 = x + selectedW;
                        const y1 = y + selectedH;

                        return [x, y, x1, y1];
                    }

                    function getJCropTrueSize(width, height, maxSize) {
                        if(width > height){
                            const defaultScale = width / height;

                            return [maxSize, maxSize/defaultScale];
                        }

                        const defaultScale = height / width;

                        return [maxSize/defaultScale, maxSize];
                    }
                });
            }
        };
    }]);

})(window.autoparusApp);