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

function menuActive() {

    var toggle = even_odd(n);

    if (toggle == true) {
        var gamElem1 = document.getElementById('g-1');
    gamElem1.className = 'gamburger g1';
    var gamElem2 = document.getElementById('g-2');
    gamElem2.className = 'gamburger g2';
    var gamElem3 = document.getElementById('g-3');
    gamElem3.className = 'gamburger g3';

    var menuElem = document.getElementById('menu');
    menuElem.className = 'nav1';
    }

    else {
        var gamElem1 = document.getElementById('g-1');
        gamElem1.className = 'gamburger';
        var gamElem2 = document.getElementById('g-2');
        gamElem2.className = 'gamburger';
        var gamElem3 = document.getElementById('g-3');
        gamElem3.className = 'gamburger';
    
        var menuElem = document.getElementById('menu');
        menuElem.className = 'nav';
    }

    n += 1;
}



function toggleShow() {

    var showBrandElem = document.getElementById('showAll');
    showBrandElem.onclick = toggleShow;

    var toggle2 = even_odd(n2);

    if (toggle2 == true) {

        var toggleButton = document.getElementById('brandImg');
        toggleButton.className = 'showLess';
       
        showBrandElem.textContent = 'Показать все >>>';

    }

    else {

       var toggleButton = document.getElementById('brandImg');
       toggleButton.className = 'showMoreBrands';

       showBrandElem.textContent = '<<< Скрыть';

    }

    n2 += 1;

}

function citiesHideShow() {

    var citiesElem = document.getElementById('hiddenCities');
    var toggle3 = even_odd(n3);
    if (toggle3 ==true) {
        citiesElem.className = 'showC';
        var showBElem = document.getElementById('showButton');
        showBElem.textContent = '<<< Скрыть';
    }
    
    else {
        citiesElem.className = 'hiddenC';
        var showBElem = document.getElementById('showButton');
        showBElem.textContent = 'Показать все >>>';
    }
    n3 += 1;

}

function text1ShowHide() {

    var text1Elem = document.getElementById('text1');

    var toggle3 = even_odd(n4);

    if(toggle3 == true) {
        text1Elem.className = 'text1Show';
        var toggleB = document.getElementById('allText');
        toggleB.textContent = '<<<<<<<';
    }
    else {
        text1Elem.className = 'text1Hide';
        var toggleB = document.getElementById('allText');
        toggleB.textContent = '>>>>>>>';
    }

    n4 +=1;

}





function toggleShow5() {

    var showPartsElem = document.getElementById('showAll5');
    showPartsElem.onclick = toggleShow5;

    var toggle2 = even_odd(n2);

    if (toggle2 == true) {

        var toggleButton = document.getElementById('brandImg5');
        toggleButton.className = 'showLess5';

        showPartsElem.textContent = '>>>>>>>';

    }

    else {

       var toggleButton = document.getElementById('brandImg5');
       toggleButton.className = 'showMoreModel';

       showPartsElem.textContent = '<<<<<<<';

    }

    n2 += 1;

}



function modelbtn() {

    var showPartsElem = document.getElementById('button-mod');
    showPartsElem.onclick = modelbtn;

    var toggle2 = even_odd(n2);

    if (toggle2 == true) {

        var toggleButton = document.getElementById('model');
        toggleButton.className = 'show';

        showPartsElem.textContent = '>>>>>>>';

    }

    else {

       var toggleButton = document.getElementById('model');
       toggleButton.className = 'show-m';

       showPartsElem.textContent = '<<<<<<<';

    }

    n2 += 1;

}

