(function(autoparusApp) {
    'use strict';

    autoparusApp.controller('InteractivSearchFormCtrl', ['$scope', '$http', '$rootScope', function($scope, $http, $rootScope) {
        this.params = {
            "brand": "",
            "model": "",
            "year": "",
            "sparePart": "",
            "engineType": "",
            "capacity": "",
            "isNew": false,
            "isUsed": false,
            "inStock": false,
        };

        let self = this;

        $rootScope.$on("change-select2-value", function(event, args) {
            let index = args.elementId.replace("-autocomplete", "");

            if(self.params.hasOwnProperty(index)){
                self.params[index] = $('#' + args.elementId).val();
            }

            $scope.$evalAsync();
        });

        function searchByForm() {
            $http({
                method: 'POST',
                url: Routing.generate('main_page_search_form'),
                data: self.params
            }).then(function (response) {
                window.location.href = response.data.redirectUrl ? response.data.redirectUrl : Routing.generate('show_brand_catalog_choice_brand');
            }, function (response) {
                console.log("error");
            });
        }

        this.searchByForm = searchByForm;
    }]);
})(window.autoparusApp);