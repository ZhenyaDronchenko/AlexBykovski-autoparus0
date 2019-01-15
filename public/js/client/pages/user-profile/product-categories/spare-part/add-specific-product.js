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


var popup1 = document.querySelector('.overlay1');

var openPopupButton1 = document.querySelector('.help');

var closePopupButton1 = popup1.querySelector('.modal-close');


openPopupButton1.addEventListener('click', function (evt) {
    popup1.classList.add('modal--show');
});

closePopupButton1.addEventListener('click', function () {
    popup1.classList.remove('modal--show');
});

document.addEventListener('keydown', function (evt) {
    if (evt.keyCode === 27) {
        popup1.classList.remove('modal--show');
    }
});




















