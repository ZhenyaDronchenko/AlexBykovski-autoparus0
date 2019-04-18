(function(autoparusApp) {
    'use strict';

    autoparusApp.controller('ImportSpecificAdvertsCtrl', ['$scope', '$http', '$q',
        function($scope, $http, $q) {
            function uploadedFile(ev) {
                upload({avatar:ev.target.files[0]}).then(function(response){
                    console.log('success :) ', response);
                }, function(){
                    console.log('failed :(');
                }, function(progress){
                    console.log('uploading: ' + Math.floor(progress) + '%');
                });

                //console.log(ev.target.files[0]);
            }

            function upload(data) {
                let formData = new FormData();
                Object.keys(data).forEach(function(key){formData.append(key, data[key]);});
                let defer = $q.defer();

                $http({
                    method: 'POST',
                    data: formData,
                    url: Routing.generate('user_office_ajax_check_is_correct_file_specific_adverts'),
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