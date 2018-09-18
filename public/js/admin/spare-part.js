$(document).ready(function (ev) {
    // checkboxes
    let checkboxes = $(".spare-part-checkboxes-after-label");

    $.each(checkboxes, function(index, input){
        let label = $(input).parents("label").find("span.control-label__text");
        let labelClone = label.clone();

        labelClone.css("padding-right", "10px");

        label.remove();
        $(input).parent().before(labelClone);
    });
});