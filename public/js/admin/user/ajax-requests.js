$(document).ready(function (ev) {
    const TOGGLE_ROLE_URL = "/admin/ajax/toggle-role/__id__";

    $("body").on("click", ".toggle-user-role-by-admin", function (event) {
        let element = $(this);
        const USER_ID = element.attr("data-user-id");
        const ROLE = element.attr("data-role");

        if(!USER_ID || !ROLE){
            return false;
        }

        $.ajax(TOGGLE_ROLE_URL.replace("__id__", USER_ID), {
            method: "POST",
            data: {
                "role" : ROLE
            },
            success(data) {
                if(data.success){
                    toggleBooleanLabel(element)
                }
                console.log(data);
            },
            error(data) {
                console.error('Error due request');
            },
        });
    });

    function toggleBooleanLabel(element) {
        const CLASS_TRUE = "label-success";
        const CLASS_FALSE = "label-danger";
        const YES_TEXT = "да";
        const NO_TEXT = "нет";

        if(element.hasClass(CLASS_TRUE)){
            return element.removeClass(CLASS_TRUE).addClass(CLASS_FALSE).text(NO_TEXT);
        }
        else if(element.hasClass(CLASS_FALSE)){
            return element.removeClass(CLASS_FALSE).addClass(CLASS_TRUE).text(YES_TEXT);
        }
    }
});