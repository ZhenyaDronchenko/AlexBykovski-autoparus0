$(document).ready(function (ev) {
    let yearFrom = $(".model-year-from");
    let yearTo = $(".model-year-to");

    if(!yearFrom.length){
        return false;
    }

    let toText = "<div class='year-to-text'> &mdash; </div>";

    yearTo.before($(toText));

    yearFrom.parents(".form-group").addClass("year-from-group");

    yearTo.parents(".form-group").addClass("year-to-group");

    yearTo.parent().addClass("label-relative-top-year-to");

    // checkboxes
    let ulCheckboxes = $(".checkboxes-after-label");

    $.each(ulCheckboxes, function(index, ul){
        if($(ul).hasClass("checkboxes-in-one-line")) {
            $(ul).addClass("list-inline");
        }

        $.each($(ul).find("li"), function (ind2, li) {
            let label = $(li).find("span.control-label__text");
            let labelClone = label.clone();
            label.remove();
            $(li).find("div.icheckbox_square-blue").before(labelClone);
        });
    });

    //engine capacity
    $.each($(".engine-capacity-container"), function(ind, item){
        if(ind == 0){
            $(item).parents("div.form-group").before($("<div class='engine-capacity-general-title'>Название [ENGINE_NAME] и Объём [ENGINE_CAPACITY]: </div>"))
        }

        $(item).parents("div.form-group").addClass("engine-capacity-container-form-group");
    });
});