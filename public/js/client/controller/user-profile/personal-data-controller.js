(function(autoparusApp) {
    'use strict';

    autoparusApp.controller('PersonalDataCtrl', ['$scope', '$http', '$sce', function($scope, $http, $sce) {
        let self = this;
        let form = null;
        let url = null;
        this.formText = "";

        console.log("in controller");

        this.init = function(formSelector, editUrl){
            form = $(formSelector);
            url = editUrl;
        };

        // function request(url, data, callback) {
        //     $http({
        //         method: 'POST',
        //         headers: {'Content-Type': 'application/x-www-form-urlencoded;charset=utf-8'},
        //         url: url,
        //         data: data
        //     }).then(function (response) {
        //         callback.call($scope, response);
        //     }, function (response) {
        //         console.log("error");
        //     });
        // }

        // function showLoginPopup() {
        //     request("/login-user", null, function (response) {
        //         setForm(response.data);
        //
        //         if(method === "login") {
        //             openPopup();
        //         }
        //
        //         handleLogin();
        //     });
        // }

        // function handleLogin(){
        //     $(loginSelector).ready(function(){
        //
        //         var formEvents = $.data($(this).get(0), 'events');
        //         var isExistSubmitHandler = !!(formEvents && formEvents.submit);
        //
        //         if(!isExistSubmitHandler){
        //             $(loginSelector).off().on("submit", function(e) {
        //                 e.preventDefault();
        //                 var data = $(loginSelector).serialize();
        //
        //                 angular.element(loginSelector).find("button[type=submit]").prop("disabled", true);
        //
        //                 request("/login-user", data, function (response) {
        //                     if(response.data.success){
        //                         if(response.data.redirect){
        //                             location.href = response.data.redirect;
        //
        //                             return true;
        //                         }
        //
        //                         $rootScope.$broadcast('user-logged-in', {user: response.data.user});
        //                         closePopup();
        //                         self.loginForm = "";
        //
        //                         return true;
        //                     }
        //                     else{
        //                         setForm(response.data);
        //                     }
        //
        //                     $(loginSelector).find("button[type=submit]").prop("disabled", false);
        //                     handleLogin();
        //                 });
        //
        //                 return false;
        //             });
        //         }
        //     });
        // }

        // function openPopup(){
        //     $.magnificPopup.open({
        //         items: {
        //             src: "#login-modal"
        //         },
        //
        //         type: 'inline',
        //
        //         fixedContentPos: false,
        //         fixedBgPos: true,
        //
        //         overflowY: 'auto',
        //
        //         closeBtnInside: true,
        //         preloader: false,
        //
        //         removalDelay: 1000,
        //
        //         mainClass: 'mfp-zoom-in',
        //         callbacks: {
        //             afterClose: function() {
        //                 if(isForgotPassword){
        //                     $rootScope.$broadcast('forgot-password-link-click');
        //                     isForgotPassword = false;
        //                 }
        //             }
        //         }
        //     });
        // }

        // function closePopup(){
        //     $.magnificPopup.close();
        // }

        // function setForm(data){
        //     if(method === "login"){
        //         self.loginForm = $sce.trustAsHtml(data);
        //         self.loginRegisterForm = "";
        //     }
        //     else{
        //         self.loginForm = "";
        //         self.loginRegisterForm = $sce.trustAsHtml(data);
        //     }
        // }

        // function clickLinkForgotPassword(){
        //     isForgotPassword = true;
        //
        //     closePopup();
        // }

        // $rootScope.$on('open-login-modal', function(){
        //     if(method === "login") {
        //         showLoginPopup();
        //     }
        // });

        // $rootScope.$on('open-registration-modal', function(){
        //     if(method === "registration") {
        //         showLoginPopup();
        //     }
        // });

        // $("body").on("click", "#linkForgotPassword", function(){
        //     clickLinkForgotPassword();
        // });

    }]);
})(window.autoparusApp);