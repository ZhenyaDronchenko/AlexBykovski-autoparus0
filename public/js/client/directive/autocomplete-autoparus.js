(function(autoparusApp) {
    'use strict';

    autoparusApp.directive("autoСompleteAutoparus",["$window", "AutoCompleteResource",
        function($window, AutoCompleteResource){
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
                        $window.location.href = '/zapchasti/' + ui.item.value;
                    },
                    classes: {
                        "ui-autocomplete": "spare-part-first-autocomplete",
                    },
                    open: function(){
                        $('.ui-autocomplete.spare-part-first-autocomplete').css('width', $("#spare-part-first-autocomplete").width() + 10 + 'px'); // HERE
                    }
                } );

            }
        };
    }]);

})(window.autoparusApp);