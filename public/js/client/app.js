// window.autoparusApp = angular.module("autoparusApp", ['ngRoute']);
//
// autoparusApp.config(function($interpolateProvider){
//     $interpolateProvider.startSymbol('{[{').endSymbol('}]}');
// });

(function(angular) {
    // window.autoparusApp = angular.module("autoparusApp", ['ngCookies', "ngSanitize", "tandibar/ng-rollbar"]);
    window.autoparusApp = angular.module("autoparusApp", ['ngCookies', "ngSanitize"]);

    autoparusApp.config(function($interpolateProvider){
        $interpolateProvider.startSymbol('{[{').endSymbol('}]}');
    });

    //https://github.com/tandibar/ng-rollbar
    // autoparusApp.config(['RollbarProvider', function(RollbarProvider) {
    //     RollbarProvider.init({
    //         accessToken: "1cb1cea2d9d74134bdbd7be8fde0bcc8",
    //         captureUncaught: true,
    //         payload: {
    //             environment: 'prod'
    //         },
    //         uncaughtErrorLevel: 'error'
    //     });
    // }]);
})(window.angular);