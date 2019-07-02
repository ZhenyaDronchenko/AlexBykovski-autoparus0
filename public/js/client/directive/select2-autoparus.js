(function(autoparusApp) {
    'use strict';

    autoparusApp.directive("select2Autoparus",["$window", "$rootScope", "AutoCompleteResource",
        function($window, $rootScope, AutoCompleteResource){
        return{
            restrict: 'A',
            link: function(scope, element, attrs)
            {
                let method = attrs.methodSearch;
                let placeholder = attrs.placeholder;
                let requestParams = angular.fromJson(attrs.requestParameters);
                let useAdditionalData = attrs.useAdditionalData === "true";
                let query = {};

                if(requestParams) {
                    addAttributesListener();
                }
                else{
                    loadData();
                }

                function createAutocomplete(dataFromServer){
                    let parsedData = parseDataFromServer(dataFromServer);

                    clearSelect2();

                    $(element).select2({
                        placeholder: placeholder,
                        data: parsedData,
                        language: {
                            searching: function (params) {
                                query = params;

                                return 'Поиск…';
                            },
                            noResults: function(){
                                return "Совпадений не найдено";
                            }
                        },
                        templateResult: function (item) {
                            if (item.loading) {
                                return item.text;
                            }

                            return markMatch(item.text, query.term || '');
                        },
                    });

                    if(parsedData.length > 1){
                        $(element).off('select2:select').on('select2:select', function (e) {
                            $rootScope.$broadcast("change-select2-value", {
                                elementId: $(element).attr("id")
                            });
                        });
                    }
                    else if(parsedData.length === 1){
                        $(element).off('select2:select');

                        $rootScope.$broadcast("change-select2-value", {
                            elementId: $(element).attr("id")
                        });
                    }
                }

                function parseDataFromServer(dataFromServer) {
                    let data = [];

                    if(dataFromServer.length > 1){
                        data.push({id: "", text: ""})
                    }

                    for(let index in dataFromServer){
                        let item = dataFromServer[index];

                        if(item.isRussian || item.text){
                            continue;
                        }

                        data.push({
                            id: item.url,
                            text: useAdditionalData ? item.value + ' ' + item.additional : item.value
                        });
                    }

                    return data;
                }

                function clearSelect2() {
                    if($(element).hasClass('select2-hidden-accessible')){
                        $(element).select2('destroy')
                            .empty()
                            .select2({
                                placeholder: placeholder,
                                language: "ru",
                            });
                    }
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
                        clearSelect2();

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