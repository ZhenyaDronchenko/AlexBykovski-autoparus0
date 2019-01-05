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

var popup1 = document.querySelector('.modal-1');
var popup2 = document.querySelector('.modal-2');
var openPopupButton1 = document.querySelector('.help-1');
var openPopupButton2 = document.querySelector('.help-2');
var closePopupButton1 = popup1.querySelector('.button-close-1');
var closePopupButton2 = popup2.querySelector('.button-close-2');

openPopupButton1.addEventListener('click', function () {
    if(popup1.classList.contains("modal--show")){
        return popup1.classList.remove('modal--show');
    }

    popup1.classList.add('modal--show');
});
openPopupButton2.addEventListener('click', function () {
    if(popup2.classList.contains("modal--show")){
        return popup2.classList.remove('modal--show');
    }

    popup2.classList.add('modal--show');
});
closePopupButton1.addEventListener('click', function () {
    popup1.classList.remove('modal--show');
});
closePopupButton2.addEventListener('click', function () {
    popup2.classList.remove('modal--show');
});





var blockShow = document.querySelector('.hidden');
var openBlockButton = document.querySelector('.show-all');

openBlockButton.addEventListener('click', function () {
    if (blockShow.style.display == "block") {
        blockShow.style.display = "none";
        openBlockButton.textContent = '>>';
        openBlockButton.classList.remove('show-active');
    } else {
        blockShow.style.display = "block";
        openBlockButton.textContent = '<<';
        openBlockButton.classList.add('show-active');
    }

});


var tab1 = document.querySelector('.tab-1');
var tab2 = document.querySelector('.tab-2');
var openTabButton1 = document.querySelector('.product-1');
var openTabButton2 = document.querySelector('.product-2');

openTabButton1.addEventListener('click', function () {
    tab1.classList.add('modal--show');
    tab2.classList.remove('modal--show');
    openTabButton2.classList.remove('active');
    openTabButton1.classList.add('active');
});

openTabButton2.addEventListener('click', function () {
    tab2.classList.add('modal--show');
    tab1.classList.remove('modal--show');
    openTabButton1.classList.remove('active');
    openTabButton2.classList.add('active');
});