$(document).ready(function (ev) {
    const EMPTY_OPTION = '<option value=""></option>';
    const PROVIDE_MODELS_LINK = "/admin/ajax/get-models-by-brand/__id__";

    let brandEl = $(".article-image-detail-brand-select");
    let modelEl = $(".article-image-detail-model-select");

    if(!brandEl.length || !brandEl.length){
        return false;
    }

    brandEl.off('change').on('change', function (ev) {
        const BRAND_ID = $(this).val();

        modelEl.html(EMPTY_OPTION);

        if(!BRAND_ID){
            return false;
        }

        $.ajax(PROVIDE_MODELS_LINK.replace("__id__", BRAND_ID), {
            method: "POST",
            success(data) {
                setModels(data);
            },
            error(data) {
                console.error('Error due request');
            },
        });
    });

    function setModels(models) {
        for(let id in models){
            let newOption = $(EMPTY_OPTION);

            newOption.val(id);
            newOption.text(models[id]);

            modelEl.append(newOption);
        }
    }

    $(".top-step").parents(".sonata-ba-field.sonata-ba-field-standard-natural").css({"padding-top" : "20px"});
    $(".detailt-types").css({"padding-top" : "20px", "padding-bottom" : "20px"});
    $(".activate-at-date").parents(".sonata-ba-field.sonata-ba-field-standard-natural").parent().css("margin-bottom", 0);

    handleActivateAt();
});

function handleActivateAt() {
    let activateAt = $(".activate-at-block").parents(".sonata-ba-field.sonata-ba-field-standard-natural").parent();
    let isActiveCheckbox = $($(".is-active-checkbox")[0]);
    console.log(isActiveCheckbox.is(":checked"));

    isActiveCheckbox.is(":checked") ? activateAt.hide() : activateAt.show();

    isActiveCheckbox.parent().find(".iCheck-helper").click(function (ev) {
        isActiveCheckbox.is(":checked") ? activateAt.hide() : activateAt.show();
    });
}