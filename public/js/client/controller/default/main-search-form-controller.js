(function(autoparusApp) {
    'use strict';

    autoparusApp.controller('MainSearchFormCtrl', ['$http', function($http) {
        this.brand = "";
        this.model = "";
        this.sparePart = "";

        let self = this;

        // $("#brand-autocomplete").on("change input copy paste", function (ev) {
        //     console.log($(this).val());
        // });

        $('#brand-autocomplete').on('autocompleteselect', function (e, ui) {
            console.log(ui.item);
            console.log(ui.item.value);
            //$('#tagsname').html('You selected: ' + this.value);
        });

        function init() {
            // $http({
            //     method: 'POST',
            //     url: url,
            //     data: params
            // }).then(function (response) {
            //     $.each(response.data, function (index, post) {
            //         $sce.trustAsHtml(post["description"]);
            //
            //         self.posts.push(waitImagesPost(post));
            //     });
            //
            //     if(self.posts.length % 8 === 0 && self.posts.length > 2){
            //         updateScrollTrigger("#post-" + self.posts[self.posts.length - 3].id);
            //     }
            //
            //     preloader.css("display", "none");
            // }, function (response) {
            //     console.log("error");
            // });
        }

        this.init = init;
    }]);
})(window.autoparusApp);