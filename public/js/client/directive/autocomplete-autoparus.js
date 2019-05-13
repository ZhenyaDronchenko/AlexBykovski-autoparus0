(function(autoparusApp) {
    'use strict';

    autoparusApp.directive("autoСompleteAutoparus",["$window", "$rootScope", "AutoCompleteResource",
        function($window, $rootScope, AutoCompleteResource){
        return{
            restrict: 'A',
            link: function(scope, element, attrs)
            {
                let addUrl = attrs.addUrl;
                let method = attrs.methodSearch;
                let identifier = attrs.identifierField;
                let requestParams = angular.fromJson(attrs.requestParameters);
                let isPreloadData = attrs.isPreloadData === "true";
                let isRussianSearch = false;

                if(addUrl) {
                    $( element ).val("");
                }

                if(isPreloadData){
                    preloadData();
                }
                else{
                    createAutocomplete(searchOnBackend);
                }

                function createAutocomplete(sourceData){
                    let autocomplete = $( element ).autocomplete({
                        source: sourceData,
                        minLength: 1,
                        classes: {
                            "ui-autocomplete": identifier,
                        },
                        open: function(){
                            $('.ui-autocomplete.' + identifier).css('width', $('#' + identifier).width() + 10 + 'px'); // HERE
                        },
                        select: function( event, ui ) {
                            $rootScope.$broadcast(identifier + '_select-in-autocomplete');
                        },
                    });

                    autocomplete.autocomplete( "instance" )._renderItem = function( ul, item ) {
                        if(!addUrl){
                            return $( "<li class='ui-menu-item'>" )
                                .html( item.label )
                                .appendTo( ul );
                        }

                        let url = addUrl[addUrl.length - 1] === "/" ? addUrl : addUrl + '/';

                        if(url.indexOf("__search-item__") > -1){
                            url = url.replace("__search-item__", item.url);
                        }
                        else{
                            url += item.url;
                        }

                        return $( "<li class='ui-menu-item'>" )
                            .append( "<a class='ui-menu-item-wrapper' href='" + url + "'>" + item.label + "</a></div>" )
                            .appendTo( ul );
                    };

                    autocomplete.autocomplete( "instance" )._renderMenu = function (ul, items) {
                        let searchValue = $(element).val();
                        searchValue = searchValue.replace(/\d+/g, '');
                        searchValue = searchValue.replace(/\s/g, '');

                        isRussianSearch = /[а-яА-ЯЁё]/.test(searchValue);

                        let self = this;

                        $.each( items, function( index, item ) {
                            if(isRussianSearch && item.hasOwnProperty("isRussian") && item.isRussian){
                                self._renderItemData( ul, item );
                            }
                            else if(!isRussianSearch && item.hasOwnProperty("isRussian") && !item.isRussian){
                                self._renderItemData( ul, item );
                            }
                            else if(!item.hasOwnProperty("isRussian")){
                                self._renderItemData( ul, item );
                            }
                        });
                    };
                }

                function searchOnBackend(request, response){
                    AutoCompleteResource[method](request.term, requestParams).then(function(items){
                        response(items);
                    });
                }

                function preloadData() {
                    AutoCompleteResource[method]("all_preload_variants", requestParams).then(function(items){
                        createAutocomplete(items)
                    });
                }
            }
        };
    }]);

})(window.autoparusApp);