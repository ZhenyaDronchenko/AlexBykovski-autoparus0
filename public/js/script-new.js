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
    menuElem.className = 'm1';
    }

    else {
        var gamElem1 = document.getElementById('g-1');
        gamElem1.className = 'gamburger';
        var gamElem2 = document.getElementById('g-2');
        gamElem2.className = 'gamburger';
        var gamElem3 = document.getElementById('g-3');
        gamElem3.className = 'gamburger';
    
        var menuElem = document.getElementById('menu');
        menuElem.className = 'm01';
    }

    n += 1;
}
function even_odd1(num) {
    if (num % 2 == 0) {
        return false;
    }
    else {
        return true;
    }  
}
function secondMenu() {

    var toggle = even_odd1(n);

    if (toggle == true) {
        var gamElem1 = document.getElementById('g-11');
    gamElem1.className = 'gamburger1 g11';
    var gamElem2 = document.getElementById('g-21');
    gamElem2.className = 'gamburger1 g21';
    var gamElem3 = document.getElementById('g-31');
    gamElem3.className = 'gamburger1 g31';

    var menuElem = document.getElementById('menu1');
    menuElem.className = 'm11';
    }

    else {
        var gamElem1 = document.getElementById('g-11');
        gamElem1.className = 'gamburger1';
        var gamElem2 = document.getElementById('g-21');
        gamElem2.className = 'gamburger1';
        var gamElem3 = document.getElementById('g-31');
        gamElem3.className = 'gamburger1';
    
        var menuElem = document.getElementById('menu1');
        menuElem.className = 'm011';
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
        toggleB.textContent = '<<<';
    }
    else {
        text1Elem.className = 'text1Hide';
        var toggleB = document.getElementById('allText');
        toggleB.textContent = '>>>';
    }

    n4 +=1;

}

function dotClick() {
    var elemLi = document.getElementById('breadcrumbs-one');
    elemLi.className = 'opened';
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
function toggleShow1() {

    var showModelElem = document.getElementById('showAll1');
    showModelElem.onclick = toggleShow1;

    var toggle2 = even_odd(n2);

    if (toggle2 == true) {

        var toggleButton = document.getElementById('brandImg1');
        toggleButton.className = 'showLess1';

        showModelElem.textContent = 'Показать все >>>';

    }

    else {

       var toggleButton = document.getElementById('brandImg1');
       toggleButton.className = 'showMoreModel';

       showModelElem.textContent = '<<< Скрыть';

    }

    n2 += 1;

}
function toggleShow2() {

    var showPartsElem = document.getElementById('showAll2');
    showPartsElem.onclick = toggleShow2;

    var toggle2 = even_odd(n2);

    if (toggle2 == true) {

        var toggleButton = document.getElementById('brandImg2');
        toggleButton.className = 'showLess2';

        showPartsElem.textContent = 'Показать все >>>';

    }

    else {

       var toggleButton = document.getElementById('brandImg2');
       toggleButton.className = 'showMoreModel';

       showPartsElem.textContent = '<<< Скрыть';

    }

    n2 += 1;

}

function toggleShow4() {

    var showPartsElem = document.getElementById('showAll4');
    showPartsElem.onclick = toggleShow4;

    var toggle2 = even_odd(n2);

    if (toggle2 == true) {

        var toggleButton = document.getElementById('brandImg4');
        toggleButton.className = 'showLess4';

        showPartsElem.textContent = '>>>>>>>';

    }

    else {

       var toggleButton = document.getElementById('brandImg4');
       toggleButton.className = 'showMoreModel';

       showPartsElem.textContent = '<<<<<<<';

    }

    n2 += 1;

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




