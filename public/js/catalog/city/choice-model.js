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

