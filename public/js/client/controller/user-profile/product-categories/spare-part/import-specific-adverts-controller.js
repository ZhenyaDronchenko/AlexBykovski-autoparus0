(function(autoparusApp) {
    'use strict';

    autoparusApp.controller('ImportSpecificAdvertsCtrl', ['$scope', '$http', '$q', function($scope, $http, $q) {
        const SUPPORT = Routing.generate('general_about_page');
        const FILE_LOADING = "Файл загружается на сервер. Пожалуйста, подождите...";
        const FILE_LOADED_SUCCESS = "Файл успешно загружен";
        const FILE_LOADED_FAILED = "При загрузке файла произошли ошибки";
        const FILE_CHECKING = "Идёт проверка файла. Пожалуйста, подождите...";
        const FILE_CHECKED_CORRECT = "Файл корректный";
        const FILE_CHECKED_FAILED = "Файл некорректный. Были обнаружены следующие ошибки:";
        const FILE_TO_IMPORT = "Чтобы импортировать данные нажмите кнопку импортировать";
        const FILE_IMPORTED_SUCCESS = "Импортировано данных успешно __count__. Вы можете проверить их <a href=\"{{ path(\"user_profile_product_categories_spare_part_list_adverts\") }}\">здесь</a>";
        const FILE_IMPORTED_FAILED = "При импорте файл произошли ошибки:";
        const FIX_ERRORS_OR_ASK = "Исправьте ошибки или обратитесь в техподдержку. Данные техподдержки вы можете найти " +
            "<a href=\"" + SUPPORT +"\">здесь</a>";

        let self = this;
        let pathToFile = "";

        this.isPossibleImport = false;
        this.isUploadAndCheckProcess = true;
        this.isImportProcess = false;
        this.fileName = "";
        this.errors = [];
        this.longActionActive = false;
        this.messageStyle = {color: 'black'};
        this.message = "";
        this.specialMessage = "";

        function uploadedFile(ev) {
            self.fileName = ev.target.files[0].name;

            uploadedFileHandler({avatar:ev.target.files[0]}).then(function(response){
                console.log(response);
            }, function(error){
                console.error(error);
            }, function(progress){
                console.log('uploading: ' + Math.floor(progress) + '%');
            });
        }

        function uploadedFileHandler(data) {
            let formData = new FormData();
            let defer = $q.defer();

            Object.keys(data).forEach(function(key){formData.append(key, data[key]);});

            $http({
                method: 'POST',
                data: formData,
                url: Routing.generate('import_ajax_check_is_correct_file_specific_adverts'),
                headers: {'Content-Type': undefined},
                uploadEventHandlers: {
                    progress: function(e) {
                        defer.notify(e.loaded * 100 / e.total);
                    }
                }
            }).then(defer.resolve.bind(defer), defer.reject.bind(defer));

            return defer.promise;
        }

        this.uploadedFile = uploadedFile;
    }]);
})(window.autoparusApp);