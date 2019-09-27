(function(autoparusApp) {
    'use strict';

    autoparusApp.directive("datalistAutocomplete",["$window", "$rootScope", "AutoCompleteResource",
        function($window, $rootScope, AutoCompleteResource){
        return{
            restrict: 'A',
            link: function(scope, element, attrs)
            {
                const OPTION = '<option value="__value__">';
                let method = attrs.methodSearch;
                let requestParams = angular.fromJson(attrs.requestParameters);

                if(requestParams) {
                    addAttributesListener();
                }
                else{
                    loadData();
                }

                function createAutocomplete(dataFromServer) {
                    let el = $(element);

                    el.html("");


                    $.each(dataFromServer, function (index, value) {
                        if(!value.isRussian && !value.text){
                            el.append($(OPTION.replace("__value__", value.value)));
                        }
                    });
                }

                function loadData() {
                    for (let param in requestParams){
                        if(!requestParams[param]){
                            return createAutocomplete([]);
                        }
                    }

                    AutoCompleteResource[method]("all_preload_variants", requestParams).then(function(items){
                        createAutocomplete(items);
                    });
                }

                function addAttributesListener() {
                    let observer = new MutationObserver(function (mutations) {
                        requestParams = angular.fromJson(attrs.requestParameters);

                        loadData();
                    });

                    observer.observe($(element)[0], {
                        attributes: true,
                        attributeFilter: ['request-parameters']
                    });
                }
            }
        };
    }]);

})(window.autoparusApp);