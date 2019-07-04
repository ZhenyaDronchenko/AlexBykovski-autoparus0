(function(autoparusApp) {
    'use strict';

    autoparusApp.directive("voiceInput",['$http', function($http){
        return{
            restrict: 'A',
            link: function(scope, element, attrs)
            {
                let trigger = $(attrs.triggerInput);
                let target = $(attrs.targetInput);
                let modal = $(attrs.modalSelector);
                let microphoneInterval = null;
                let textPlace = $("#text-from-microphone");
                let recognition = null;

                if (window.hasOwnProperty('webkitSpeechRecognition')) {
                    recognition = new webkitSpeechRecognition();
                    recognition.interimResults = true;
                    recognition.continuous = true;

                    recognition.lang = "ru-RU";
                }

                trigger.click(function () {
                    target.trigger("search");
                    return false;
                    if(recognition) {
                        recognition.start();

                        recognition.onsoundstart = function (e) {
                            if (modal.length) {
                                textPlace.html("");
                                modal.show();
                                startWorkMicrophone();
                            }
                        };

                        recognition.onresult = function (e) {
                            let value = e.results[0][0].transcript;

                            if (value) {
                                target.val(e.results[0][0].transcript);

                                if (modal.length) {
                                    textPlace.html(e.results[0][0].transcript);
                                }
                            }
                        };

                        recognition.onspeechend = function (e) {
                            recognition.stop();

                            let value = e.results ? e.results[0][0].transcript : "";
                            value = value ? value : textPlace.html();

                            if (value) {
                                target.val(value);
                                target.attr("data-by-voice", "true");

                                if (modal.length) {
                                    clearInterval(microphoneInterval);

                                    textPlace.html(value);
                                }

                                target.trigger("search");
                            }
                        };

                        recognition.onerror = function (e) {
                            recognition.stop();
                        }
                    }
                });

                target.on("search", function (e) {
                    console.log($(this).val());

                    $http({
                        method: 'POST',
                        url: Routing.generate('search_by_voice_speech'),
                        data: {
                            text: $(this).val()
                        }
                    }).then(function (response) {
                        console.log(response.data);

                    }, function (response) {
                        console.error(response);
                    });


                    //window.location = "/brand-catalog";
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

                $("#microphone-image").click(function (e) {
                    recognition.stop();
                });
            }
        };
    }]);

})(window.autoparusApp);