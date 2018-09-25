// window.autoparusApp = angular.module("autoparusApp", ['ngRoute']);
//
// autoparusApp.config(function($interpolateProvider){
//     $interpolateProvider.startSymbol('{[{').endSymbol('}]}');
// });

(function(angular) {
    window.autoparusApp = angular.module("autoparusApp", []);

    autoparusApp.config(function($interpolateProvider){
        $interpolateProvider.startSymbol('{[{').endSymbol('}]}');
    });
})(window.angular);