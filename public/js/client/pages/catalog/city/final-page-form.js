// start scroll-to-element
const URL = "/city-catalog-ajax/handle-request";
const FORM_SELECTOR = "#form-request";
const container = $("#form-container");


$(document).ready(function(ev){
    sendForm();

    function request(data, callback){
        $.ajax({
            method: 'POST',
            headers: {'Content-Type': 'application/x-www-form-urlencoded;charset=utf-8'},
            url: URL,
            data: data
        }).done(function(data) {
            callback(data);
        })
        .fail(function(xhr) {
            console.log('error', xhr);
        });
    }


    function handleForm(){
        $(FORM_SELECTOR).ready(function(){
            addDefaultHideBlocks();
            collectionHandler();
            let formEvents = $.data($(this).get(0), 'events');
            let isExistSubmitHandler = !!(formEvents && formEvents.submit);

            if(!isExistSubmitHandler){
                $(".phone-mask-by").mask("+375  (99)  999 - 99 - 99");
                $(".phone-mask-ru").mask("+7  (999)  999 - 99 - 99");

                $(FORM_SELECTOR).off().on("submit", function(e) {
                    e.preventDefault();

                    sendForm();

                    return false;
                });
            }
        });
    }

    function sendForm() {
        let form = $(FORM_SELECTOR);
        let data = {};

        if(form) {
            data = form.serialize();

            $(FORM_SELECTOR).find("button[type=submit]").prop("disabled", true);
        }

        request(data, function (response) {
            container.html("").append(response);
            handleForm();

            $(FORM_SELECTOR).find("button[type=submit]").prop("disabled", false);
        });
    }

    function addDefaultHideBlocks() {
        $("#table-data").html($("#table-data-hide").html());
        $("#below-button").html($("#below-button-hide").html());
    }


    //collection add/remove
    function collectionHandler() {
        let collectionHolder = $("#spare-part-collection");
        let addButton = $("#add-new-spare-part-button");

        collectionHolder.data('index', collectionHolder.find('.spare-part-container').length);
        addButton.click(function(e) {

            addSparePartForm(collectionHolder);
        });

        $("body").on('click', ".remove-spare-part-button", function(e) {
            $(this).parents(".spare-part-container").remove();
        });
    }

    function addSparePartForm(collectionHolder) {
        let prototype = $("#spare-part-prototype-container").html();
        let index = collectionHolder.data('index');
        let newForm = prototype;

        newForm = newForm.replace(/__index__/g, index);

        collectionHolder.data('index', index + 1);

        collectionHolder.append(newForm);
    }

    $("body").on("change", "input[list='sp-list']", function (ev) {
        let parent = $(this).parents(".spare-part-container");

        if($(this).val() !== "" && !parent.find(".spare-part-number").is("visible")){
            parent.find(".spare-part-number").show();
            parent.find(".spare-part-comment").show();
        }
    });
});