// start scroll-to-element
$(document).ready(function(ev){
    handleScrollToElement()
});

function handleScrollToElement() {
    $.each($("[scroll-to-element]"), function (index, element) {
        scrollToElement(element);
    })
}

function scrollToElement(element) {
    let elObj = $(element);
    let autoFocus = elObj.attr("auto-focus") ? $(elObj.attr("auto-focus")) : null;
    let isScrollByClick = !!elObj.attr("scroll-by-click");
    let initiator = elObj.attr("initiator") ? $(elObj.attr("initiator")) : elObj;
    let beforeTop = elObj.attr("before-top") ? $(elObj.attr("before-top")) : 0;

    $(document).ready(function(ev){
        if(!isScrollByClick){
            scroll(element, autoFocus, beforeTop);
        }
        else {
            initiator.click(function (ev) {
                scroll(element, autoFocus, beforeTop);
            });
        }
    });
}

function scroll(element, autoFocus, beforeTop){
    $('html, body').animate({
        scrollTop: $(element).offset().top - beforeTop
    }, 1000, "swing", function(){
        if(autoFocus && !autoFocus.is(":focus")){
            //@@todo: not working for dynamic add elements
            autoFocus.focus();
        }
    });
}

// end scroll-to-element

// start datalist-autocomplete
const OPTION = '<option data-url="/__url__" value="__value__">';

$(document).ready(function(ev){
    $.each($("[datalist-autocomplete]"), function (index, element) {
        datalistAutocomplete(element);
    });
});

function datalistAutocomplete(element) {
    let elObj = $(element);
    let method = elObj.attr("method-search");
    let requestParams = elObj.attr("request-parameters") ? JSON.parse(elObj.attr("request-parameters")) : null;
    let addUrl = elObj.attr("add-url") ? elObj.attr("add-url") : '';
    let isPreloadData = elObj.attr("is-preload-data") === "true";

    if(requestParams) {
        addAttributesListener(elObj, method, isPreloadData);
    }
    else{
        loadData(requestParams, elObj, method);
    }

    $("input[list=" + elObj.attr("id") + "]").on("change", function (ev) {
        let choice = elObj.find("option[value='" + $(this).val() + "']");

        if(choice.length > 0){
            window.location.href = addUrl + choice.attr("data-url");
        }
    });
}

function createAutocomplete(dataFromServer, element) {
    let el = $(element);

    el.html("");


    $.each(dataFromServer, function (index, value) {
        if(!value.isRussian && !value.text){
            el.append($(OPTION.replace("__value__", value.value).replace("__url__", value.url)));
        }
    })
}

function loadData(requestParams, element, method) {
    for (let param in requestParams){
        if(!requestParams[param]){
            return createAutocomplete([], element);
        }
    }

    (new AutoCompleteResource)[method]("all_preload_variants", requestParams, function (items) {
        createAutocomplete(items, element);
    });
}

function addAttributesListener(element, method, isPreloadData) {
    let observer = new MutationObserver(function (mutations) {
        let requestParams = JSON.parse(element.attr("request-parameters"));

        console.log(requestParams);
        loadData(requestParams, element, method);
    });

    observer.observe($(element)[0], {
        attributes: true,
        attributeFilter: ['request-parameters']
    });

    if(isPreloadData){
        loadData(JSON.parse(element.attr("request-parameters")), element, method);
    }
}

// end datalist-autocomplete

function showBySelector(selector) {
    $(selector).show();
}

function hideBySelector(selector) {
    $(selector).hide();
}