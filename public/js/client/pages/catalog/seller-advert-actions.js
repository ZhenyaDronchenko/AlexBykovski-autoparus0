$(document).ready(function(){
    const DEFAULT_DELIVERY = "Возможность, условия и стоимость доставки уточняйте у продавца по телефону";
    const DEFAULT_GUARANTEE = "Гарантийные обязательства, возможность и условие возврата или замены товара уточняйте у продавца по телефону";

    let classShowModal = "modal--show";
    let addressModal = $("#seller-address-modal");
    let deliveryGuaranteeModal = $("#seller-delivery-guarantee-modal");
    let existDeliveryGuarantee = $("#exist-delivery-guarantee");
    let absentDeliveryGuarantee = $("#absent-delivery-guarantee");

    $(".show-seller-address").click(function(ev){
        let address = $(this).attr("data-address");

        let iframe = "<iframe src='https://maps.google.com/maps?&amp;q="+ encodeURIComponent( address ) + "&amp;output=embed'></iframe>";

        $("#seller-address").html(iframe);

        addressModal.addClass(classShowModal);
    });

    $("#close-seller-address-modal").click(function (ev) {
        addressModal.removeClass(classShowModal);
    });

    $(".show-seller-delivery-guarantee").click(function (ev) {
        let delivery = $(this).attr("data-delivery");
        let guarantee = $(this).attr("data-guarantee");

        $("#delivery-text").html(delivery ? delivery: DEFAULT_DELIVERY);
        $("#guarantee-text").html(guarantee ? guarantee : DEFAULT_GUARANTEE);

        if(!delivery && !guarantee){
            existDeliveryGuarantee.hide();
            absentDeliveryGuarantee.show();
        }
        else{
            existDeliveryGuarantee.show();
            absentDeliveryGuarantee.hide();
        }

        deliveryGuaranteeModal.addClass(classShowModal);
    });

    $("#close-seller-delivery-guarantee-modal").click(function (ev) {
        deliveryGuaranteeModal.removeClass(classShowModal);
    });

    $(document).on("click", ".click-ok-phone", function (ev) {
        console.log("here");
        let ok = '<svg class="contact-img" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" viewBox="0 0 50 50" version="1.1" width="15px" height="15px">' +
            '<path fill="#000" style=" " d="M 41.9375 8.625 C 41.273438 8.648438 40.664063 9 40.3125 9.5625 L 21.5 38.34375 L 9.3125 27.8125 C 8.789063 27.269531 8.003906 27.066406 7.28125 27.292969 C 6.5625 27.515625 6.027344 28.125 5.902344 28.867188 C 5.777344 29.613281 6.078125 30.363281 6.6875 30.8125 L 20.625 42.875 C 21.0625 43.246094 21.640625 43.410156 22.207031 43.328125 C 22.777344 43.242188 23.28125 42.917969 23.59375 42.4375 L 43.6875 11.75 C 44.117188 11.121094 44.152344 10.308594 43.78125 9.644531 C 43.410156 8.984375 42.695313 8.589844 41.9375 8.625 Z "/>' +
            '</svg>';

        $(this).append(ok);
        $(this).removeClass("click-ok-phone");
    })
});