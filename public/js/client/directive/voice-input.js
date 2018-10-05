(function(autoparusApp) {
    'use strict';

    autoparusApp.directive("voiceInput",[function(){
        return{
            restrict: 'A',
            link: function(scope, element, attrs)
            {
                let trigger = $(attrs.triggerInput);
                let target = $(attrs.targetInput);

                trigger.click(function () {
                    if (window.hasOwnProperty('webkitSpeechRecognition')) {
                        let recognition = new webkitSpeechRecognition();

                        recognition.continuous = false;
                        recognition.interimResults = false;

                        recognition.lang = "ru-RU";
                        recognition.start();

                        recognition.onresult = function(e) {
                            recognition.stop();

                            target.val(e.results[0][0].transcript)
                        };

                        recognition.onerror = function(e) {
                            recognition.stop();
                        }
                    }
                });
            }
        };
    }]);

})(window.autoparusApp);