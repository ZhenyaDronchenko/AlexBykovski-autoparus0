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
                    $( element ).autocomplete({
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
                        }
                    })
                        .autocomplete( "instance" )._renderItem = function( ul, item ) {
                        if(!addUrl){
                            return $( "<li class='ui-menu-item'>" )
                                .html( item.label )
                                .appendTo( ul );
                        }

                        let url = addUrl[addUrl.length - 1] === "/" ? addUrl + item.url : addUrl + '/' + item.url ;

                        return $( "<li class='ui-menu-item'>" )
                            .append( "<a class='ui-menu-item-wrapper' href='" + url + "'>" + item.label + "</a></div>" )
                            .appendTo( ul );
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