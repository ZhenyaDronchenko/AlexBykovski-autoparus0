(function(autoparusApp) {
    'use strict';

    autoparusApp.controller('ImportSpecificAdvertsCtrl', ['$scope', '$http', '$timeout', function($scope, $http, $timeout) {
        const FILE_LOADING = "Файл загружается на сервер. Пожалуйста, подождите...";
        const FILE_LOADED_SUCCESS = "Файл успешно загружен";
        const FILE_LOADED_FAILED = "При загрузке файла произошли ошибки: ";
        const FILE_CHECKING = "Идёт проверка файла. Пожалуйста, подождите...";
        const FILE_CHECKED_CORRECT = "Файл корректный. Чтобы импортировать данные нажмите кнопку импортировать";
        const FILE_CHECKED_FAILED = "Файл некорректный. Были обнаружены следующие ошибки:";
        const FILE_IMPORTED_SUCCESS = "Импортировано данных успешно __count__. Вы можете проверить их <a href=\"{{ path(\"user_profile_product_categories_spare_part_list_adverts\") }}\">здесь</a>";
        const FILE_IMPORTED_FAILED = "При импорте файл произошли ошибки:";

        let self = this;
        let pathToFile = "";

        this.isPossibleImport = false;
        this.isUploadAndCheckProcess = false;
        this.isImportProcess = false;
        this.fileName = "";
        this.errors = [];
        this.longActionActive = false;
        this.messageStyle = {color: 'black'};
        this.message = "";
        this.showSpecialMessage = false;

        function uploadedFile(ev) {
            resetData();

            self.fileName = ev.target.files[0].name;

            let formData = new FormData();
            formData.append("file", ev.target.files[0]);

            self.message = FILE_LOADING;
            self.longActionActive = true;
            self.isUploadAndCheckProcess = true;

            $http({
                method: 'POST',
                data: formData,
                url: Routing.generate('import_ajax_upload_file_specific_adverts'),
                headers: {'Content-Type': undefined}
            }).then(function (response) {
                console.log(response);
                self.longActionActive = false;

                if(response.data.success){
                    handleResponse(FILE_LOADED_SUCCESS);
                    pathToFile = response.data.file;

                    $timeout(function(){
                        checkFile();
                    }, 1500);
                }
                else{
                    handleResponse(FILE_LOADED_FAILED, response.data.errors, true);
                    self.isUploadAndCheckProcess = false;
                }
            }, function (response) {
                self.longActionActive = false;
                self.isUploadAndCheckProcess = false;

                console.error(response);

                handleResponse(FILE_LOADED_FAILED, null, true);
            });
        }

        function checkFile() {
            self.longActionActive = true;
            self.message = FILE_CHECKING;

            $http({
                method: 'POST',
                data: {"path" : pathToFile},
                url: Routing.generate('import_ajax_check_is_correct_file_specific_adverts'),
            }).then(function (response) {
                console.log(response);
                self.longActionActive = false;
                self.isUploadAndCheckProcess = false;

                if(response.data.success){
                    handleResponse(FILE_CHECKED_CORRECT);
                    pathToFile = response.data.file;
                    self.isPossibleImport = true;
                }
                else{
                    handleResponse(FILE_CHECKED_FAILED, response.data.errors, true);
                }

            }, function (response) {
                self.longActionActive = false;
                self.isUploadAndCheckProcess = false;

                console.error(response);

                handleResponse(FILE_CHECKED_FAILED, null, true);
            });
        }

        function handleResponse(message, errors, showSpecial) {
            self.errors = errors ? errors : [];
            self.message = message;
            self.messageStyle.color = errors ? "black" : "green";
            self.showSpecialMessage = !!showSpecial;
        }

        function resetData() {
            self.isPossibleImport = false;
            self.isUploadAndCheckProcess = true;
            self.isImportProcess = false;
            self.errors = [];
            self.longActionActive = false;
            self.messageStyle = {color: 'black'};
            self.message = "";
            self.showSpecialMessage = false;
        }

        this.uploadedFile = uploadedFile;
    }]);
})(window.autoparusApp);