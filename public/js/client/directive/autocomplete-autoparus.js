(function(autoparusApp) {
    'use strict';

    autoparusApp.directive("autoСompleteAutoparus",["$window", "AutoCompleteResource",
        function($window, AutoCompleteResource){
        return{
            restrict: 'A',
            link: function(scope, element, attrs)
            {
                let addUrl = attrs.addUrl;
                let method = attrs.methodSearch;
                let identifier = attrs.identifierField;
                let requestParams = angular.fromJson(attrs.requestParameters);

                if(addUrl) {
                    $( element ).val("");
                }

                $( element ).autocomplete({
                    source: function( request, response ) {
                        AutoCompleteResource[method](request.term, requestParams).then(function(items){
                            response(items);
                        });
                    },
                    minLength: 1,
                    classes: {
                        "ui-autocomplete": identifier,
                    },
                    open: function(){
                        $('.ui-autocomplete.' + identifier).css('width', $('#' + identifier).width() + 10 + 'px'); // HERE
                    }
                })
                .autocomplete( "instance" )._renderItem = function( ul, item ) {
                    if(!addUrl){
                        return $( "<li class='ui-menu-item'>" )
                            .html( item.label )
                            .appendTo( ul );
                    }

                    return $( "<li class='ui-menu-item'>" )
                        .append( "<a class='ui-menu-item-wrapper' href='" + addUrl + item.url + "'>" + item.label + "</a></div>" )
                        .appendTo( ul );
                };

            }
        };
    }]);

})(window.autoparusApp);