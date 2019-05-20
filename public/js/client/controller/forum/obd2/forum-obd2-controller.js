(function(autoparusApp) {
    'use strict';

    autoparusApp.controller('ForumOBD2Ctrl', ["$scope", "$http", "$compile",
        function($scope, $http, $compile) {
        const ADD_COMMENT_LINK = Routing.generate('ajax_obd2_forum_add_comment_to_message', {"id" : "__id__"});
        let self = this;
        let addMessageLink = "";

        this.messages = {};
        this.showCommentForm = {};
        this.constModel = null;

        function init(getMessagesUrl, addMessageLinkS) {
            getAllMessages(getMessagesUrl);

            addMessageLink = addMessageLinkS;
        }

        this.resetAddMessageForm = function(){
            this.model = null;
            this.message = null;
            this.comment = null;
            this.showEmptyModelError = false;
            this.showEmptyMessageError = false;
            this.showEmptyCommentError = false;
        };

        this.resetAddMessageForm();

       function addMessage(type){
           if(!validateMessageRequest()){
               return false;
           }

           $(".message-popup").hide();

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
           if(self.constModel){
               self.model = self.constModel;
           }

           self.showEmptyModelError = !self.model;
           self.showEmptyMessageError = !self.message;

           return !self.showEmptyModelError && !self.showEmptyMessageError;
       }

       function getAllMessages(getMessagesUrl) {
           $.ajax(getMessagesUrl, {
               method: "GET",
               success(data) {
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

       function addComment(messageId) {
           if(!messageId || !validateCommentRequest()){
               return false;
           }

           $.ajax(ADD_COMMENT_LINK.replace("__id__", messageId), {
               method: "POST",
               data: {"comment" : self.comment},
               success(data) {
                   if(data.success) {
                       self.messages[data.message.id] = data.message;
                       self.showCommentForm[data.message.id] = false;

                       $scope.$evalAsync();
                   }
               },
               error(data) {
                   console.error('Upload error');
               },
           });
       }

            function validateCommentRequest() {
                self.showEmptyCommentError = !self.comment;

                return !self.showEmptyCommentError;
            }

       this.addMessage = addMessage;
       this.addComment = addComment;
       this.init = init;
    }]);
})(window.autoparusApp);