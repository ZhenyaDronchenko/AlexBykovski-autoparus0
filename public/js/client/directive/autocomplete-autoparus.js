(function(autoparusApp) {
    'use strict';

    autoparusApp.directive("autoСompleteAutoparus",["AutoCompleteResource", function(AutoCompleteResource){
        return{
            restrict: 'A',
            link: function(scope, element, attrs)
            {
                $( element ).autocomplete({
                    source: function( request, response ) {
                        AutoCompleteResource.searchSpareParts(request.term).then(function(spareParts){
                            response(spareParts);
                        });
                    },
                    minLength: 1,
                    select: function( event, ui ) {
                        console.log( "Selected: " + ui.item.value);
                    }
                } );

            }
        };
    }]);

})(window.autoparusApp);