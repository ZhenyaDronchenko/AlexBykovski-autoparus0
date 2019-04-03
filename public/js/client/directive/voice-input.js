(function(autoparusApp) {
    'use strict';

    autoparusApp.directive("voiceInput",[function(){
        return{
            restrict: 'A',
            link: function(scope, element, attrs)
            {
                let trigger = $(attrs.triggerInput);
                let target = $(attrs.targetInput);
                let modal = $(attrs.modalSelector);
                let microphoneInterval = null;
                let textPlace = $("#text-from-microphone");

                trigger.click(function () {
                    if (window.hasOwnProperty('webkitSpeechRecognition')) {
                        let recognition = new webkitSpeechRecognition();

                        recognition.continuous = false;
                        recognition.interimResults = false;

                        recognition.lang = "ru-RU";
                        recognition.start();

                        recognition.onsoundstart = function(e) {
                            if(modal.length) {
                                textPlace.html("");
                                modal.show();
                                startWorkMicrophone();
                            }
                        };

                        recognition.onresult = function(e) {
                            recognition.stop();

                            let value = e.results[0][0].transcript;

                            if(value){
                                target.val(e.results[0][0].transcript);
                                target.attr("data-by-voice", "true");

                                if(modal.length){
                                    clearInterval(microphoneInterval);

                                    textPlace.html(e.results[0][0].transcript);
                                }

                                target.trigger("search");
                            }
                        };

                        recognition.onerror = function(e) {
                            recognition.stop();
                        }
                    }
                });

                target.on("search", function () {
                    window.location = "/brand-catalog";
                });


                function startWorkMicrophone() {
                    let el = $("#microphone-image");
                    let shadow1 = 10;
                    let shadow2 = 5;
                    let moveUp = true;

                    microphoneInterval = setInterval(function () {
                        if(shadow1 === 20){
                            moveUp = false;
                        }
                        else if(shadow1 === 10){
                            moveUp = true;
                        }

                        if(moveUp){
                            shadow1 += 2;
                            shadow2 += 1;
                        }
                        else{
                            shadow1 -=2;
                            shadow2 -=1;
                        }

                        el.css("box-shadow", "0px 0px " + shadow1 + "px " + shadow2 + "px rgba(68,68,68,0.6)")
                    }, 100)
                }
            }
        };
    }]);

})(window.autoparusApp);