(function(autoparusApp) {
    'use strict';

    autoparusApp.controller('ForumOBD2Ctrl', ["$scope", "$http", "$compile",
        function($scope, $http, $compile) {

        let self = this;
        let addMessageLink = "";

        this.messages = {};

        function init(getMessagesUrl, addMessageLinkS) {
            getAllMessages(getMessagesUrl);

            addMessageLink = addMessageLinkS;
        }

        this.resetAddMessageForm = function(){
            this.model = null;
            this.message = null;
            this.showEmptyModelError = false;
            this.showEmptyMessageError = false;
        };

        this.resetAddMessageForm();

       function addMessage(type){
           if(!validateMessageRequest()){
               return false;
           }

           $.ajax(addMessageLink.replace("__model__", self.model), {
               method: "POST",
               data: {"type" : type, "message" : self.message},
               success(data) {
                   if(data.success) {
                       self.messages[data.message.id] = data.message;

                       $scope.$evalAsync();
                   }
               },
               error(data) {
                   console.error('Upload error');
               },
           });
       }

       function validateMessageRequest() {
           self.showEmptyModelError = !self.model;
           self.showEmptyMessageError = !self.message;

           return !self.showEmptyModelError && !self.showEmptyMessageError;
       }

       function getAllMessages(getMessagesUrl) {
           $.ajax(getMessagesUrl, {
               method: "GET",
               success(data) {
                   console.log(data);
                   if(data.success) {
                       self.messages = data.messages;

                       $scope.$evalAsync();
                   }
               },
               error(data) {
                   console.error('Upload error');
               },
           });
       }

       this.addMessage = addMessage;
       this.init = init;
    }]);
})(window.autoparusApp);