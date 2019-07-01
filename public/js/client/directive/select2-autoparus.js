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
                let query = {};

                if(requestParams) {
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
                else{
                    loadData();
                }

                function createAutocomplete(dataFromServer){
                    let data = [{id: "", text: ""}];
                    //let data = [];

                    for(let index in dataFromServer){
                        let item = dataFromServer[index];

                        if(item.isRussian || item.text){
                            continue;
                        }

                        data.push({id: item.url, text: item.value});
                    }

                    clearSelect2();

                    $(element).select2({
                        placeholder: placeholder,
                        language: {
                            searching: function (params) {
                                query = params;

                                return 'Поиск…';
                            },
                            noResults: function(){
                                return "Совпадений не найдено";
                            }
                        },
                        data: data,
                        templateResult: function (item) {
                            if (item.loading) {
                                return item.text;
                            }

                            return markMatch(item.text, query.term || '');
                        },
                    });

                    $(element).on('select2:select', function (e) {
                        $rootScope.$broadcast("change-select2-value", {
                            elementId: $(element).attr("id")
                        });
                    });

                    $(element).on('select2:opening', function (e) {
                        $('html, body').animate({
                            scrollTop: $(element).offset().top - 30
                        }, 1000, "swing");
                    });
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

                function f() {

                }
            }
        };
    }]);

})(window.autoparusApp);