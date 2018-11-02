'use strict'

var n = 1;
var n2 = 2;
var n3 = 1;
var n4 = 1;


function even_odd(num) {
    if (num % 2 == 0) {
        return false;
    }
    else {
        return true;
    }
}

$('document').ready(function(){
    let main = $("main.container");
    $('.podskazka').hide();
    $('.choice-new .button').click(function(){
        let value = $(this).html();

        if(value.indexOf('Отметить все модели') > -1){
            $(this).find("span.first-span").html("Снять выбор всех моделей");
            $('input.checkbox.model-checkbox').prop('checked', true);
        } else{
            $(this).find("span.first-span").html("Отметить все модели");
            $('input.checkbox.model-checkbox').prop('checked', false);
        }
    });

    $('.button-m').click(function(){
        let value = $(this).html();

        if(value === 'Отметить все запчасти (товары)'){
            $(this).html('Снять выбор запчастей (товаров)');
            $('input.checkbox_cl').prop('checked', true);
        } else{
            $(this).html('Отметить все запчасти (товары)');
            $('input.checkbox_cl').prop('checked', false);
        }
    });

    $('.choice-marka a').click(function(){
        $('.podskazka').show();
        $('.close').click(function(){
            $('.podskazka').hide();
        })
    });

    $('.block-title a').click(function(){
        $('.podskazka-m').show();
        $('.close-m').click(function(){
            $('.podskazka-m').hide();
        })
    });

    if(main.attr("redirect-page")){
       window.location.href = main.attr("redirect-page");
    }

});

function dotClick() {
    var elemLi = document.getElementById('breadcrumbs');
    elemLi.className = 'opened';
}

