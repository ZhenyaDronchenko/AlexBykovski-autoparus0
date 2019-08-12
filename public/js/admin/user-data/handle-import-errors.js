$(document).ready(function (ev) {
    handleBrandSolutions();
    handleModelSolutions();
    handleSparePartSolutions();
});

function handleBrandSolutions() {
    let brands = $(".error-solution.brand-solution");

    if(!brands.length){
        return false;
    }

    getBrandSearch(function (response) {
        setSelect2(brands, parseBrandsFromServer(response));
    });
}

function handleModelSolutions() {
    let models = $(".error-solution.model-solution");
    let modelArrays = {};

    if(!models.length){
        return false;
    }

    $.each(models, function (index, item) {
        let brandUrl = $(item).data("options")["brand"];

        if(!modelArrays.hasOwnProperty(brandUrl)){
            modelArrays[brandUrl] = [];
        }

        modelArrays[brandUrl].push($(item));
    });

    $.each(modelArrays, function (index, modelArray) {
        let brandUrl = modelArray[0].data("options")["brand"];

        getModelSearch(function (response) {
            setSelect2(modelArrays[brandUrl], parseBrandsFromServer(response), {"multiple" : true});
        }, brandUrl);
    });
}

function handleSparePartSolutions() {
    let spareParts = $(".error-solution.spare-part-solution");

    if(!spareParts.length){
        return false;
    }

    getSparePartSearch(function (response) {
        setSelect2(spareParts, parseBrandsFromServer(response));
    });
}

function getBrandSearch(callback) {
    callAjaxSearch("/search/brand?text=all_preload_variants", function (response) {
        callback(response);
    });
}

function getSparePartSearch(callback) {
    callAjaxSearch("/search/spare-part?text=all_preload_variants", function (response) {
        callback(response);
    });
}

function getModelSearch(callback, brandUrl) {
    callAjaxSearch("/search/model/" + brandUrl + "?text=all_preload_variants", function (response) {
       callback(response);
    });
}

function callAjaxSearch(url, callback) {
    $.ajax({
        url: url,
    }).done(function(response) {
        callback(response);
    });
}

function parseBrandsFromServer(dataFromServer) {
    let data = [{id: "", text: ""}];

    for(let index in dataFromServer){
        let item = dataFromServer[index];

        if(item.isRussian || item.text){
            continue;
        }

        data.push({
            id: item.id,
            text: item.value
        });
    }

    return data;
}

function setSelect2(elements, data, additionalOptions) {
    $.each(elements, function (index, item) {
        let options = {
            data: data,
            containerCssClass: "select2-error-import-solution"
        };

        if(additionalOptions) {
            options = Object.assign(additionalOptions, options);
        }
        $(item).select2(options);
    });
}

function saveSolution(id, simpleRemove) {
    let element = $("#solution-" + id);
    let value = element.length ? element.val() : "";
    let type = element.length ? element.data("type") : "";

    $.ajax({
        url: "/admin-save-keywords-import-advert-error/" + id,
        method: "POST",
        data: {
            value : simpleRemove ? "" : value,
            type : simpleRemove ? "" : type
        },
    }).done(function(response) {
        if(response.success){
            $(".remove-button-" + id).parents("td.sonata-ba-list-field[objectid=" + id + "]").parent().remove();
        }
    });
}