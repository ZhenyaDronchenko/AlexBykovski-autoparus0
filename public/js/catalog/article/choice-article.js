
var modal = document.querySelector('.select');

var openPopupButton = document.querySelector('.select-open');



openPopupButton.addEventListener('click', function (evt) {
    evt.preventDefault();
    modal.classList.add('modal--show');
    openPopupButton.classList.add('modal--hidden');
  });


