(function(autoparusApp) {
    'use strict';

    autoparusApp.controller('ArticleCommentCtrl', ['$scope', '$http', function($scope, $http) {
        const GET_ALL_LINK = Routing.generate('article_catalog_get_comments', {"id" : "__aid__"});
        const SEND_COMMENT_LINK = Routing.generate('article_catalog_send_comment', {"id" : "__aid__"});

        this.comments = {};
        this.commentText = {};
        this.showReply = {};
        this.count = 0;

        let articleId = null;
        let self = this;
        let authMessage = $("#show-buttons-auth");

        function init(articleIdS) {
            articleId = articleIdS;

            loadComments();
        }

        function sendComment(id) {
            if(authMessage.length){
                return authMessage.show();
            }

            if(!id || !self.commentText[id]){
                return false;
            }

            $http({
                method: 'POST',
                url: SEND_COMMENT_LINK.replace("__aid__", articleId),
                data: {
                    "text" : self.commentText[id],
                    "parent" : id === "new" ? null : id,
                }
            }).then(function (response) {
                if(response.data.success){
                    let newComment = response.data.message;

                    if(newComment.parent){
                        self.comments[newComment.parent]["children"].push(newComment);
                        self.showReply[newComment.parent] = false;
                    }
                    else{
                        self.comments[newComment.id] = newComment;
                    }

                    self.commentText[id] = "";
                    ++self.count;
                }

            }, function (response) {
                console.error(response);
            });
        }

        function loadComments() {
            $http({
                method: 'POST',
                url: GET_ALL_LINK.replace("__aid__", articleId),
//                data: params
            }).then(function (response) {
                self.comments = Array.isArray(response.data) ? {} : response.data;
                console.log(self.comments);

                calculateCount();
            }, function (response) {
                console.error(response);
            });
        }

        function calculateCount() {
            $.each(self.comments, function (index, comment) {
                ++self.count;

                self.count += comment["children"].length;
            });
        }

        this.init = init;
        this.sendComment = sendComment;
    }]);
})(window.autoparusApp);