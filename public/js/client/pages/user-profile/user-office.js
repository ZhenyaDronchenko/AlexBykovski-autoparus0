$("#user-map-link").click(function () {
    navigator.geolocation.getCurrentPosition(function(position) {});
});

navigator.permissions.query({name:'geolocation'})
    .then(function(permissionStatus) {
        if(permissionStatus !== "granted"){
            $("#user-map-link").show();
        }

        permissionStatus.onchange = function() {
            if(this.state === "granted") {
                $("#user-map-link").hide();
            }
            else{
                $("#user-map-link").show();
            }
        };
    });