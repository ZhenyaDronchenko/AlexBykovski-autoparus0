 'use strict'

var n = 1;
var n2 = 2;

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

function secondMenu() {

    var elem = document.getElementById('menu');
    var styles = elem.classList.contains('m01');

    if(styles == true) {
        menuActive();
    }
    else {
        return;
    }
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









