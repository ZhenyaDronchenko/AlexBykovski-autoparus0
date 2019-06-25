(function (autoparusApp) {
    'use strict';

    autoparusApp.directive("showMoreLines", [function () {
        return {
            restrict: 'A',
            scope: true,
            link: function (scope, element, attrs) {
                let triggerOpen = attrs.triggerOpen;
                let triggerClose = attrs.triggerClose;
                let countLines = parseInt(attrs.countLines);
                let classLess = attrs.classLess;
                let classMore = attrs.classMore;
                let lines = 0;
                getCountLines();

                $(triggerOpen).ready(function () {
                   if(lines > countLines){
                        $(element).removeClass(classMore).addClass(classLess);
                        $(triggerOpen).show();
                    }

                    $(triggerOpen)[0].onclick = function() { // перезапишет существующий обработчик
                        $(element).removeClass(classLess).addClass(classMore);
                        $(triggerClose).show();
                        $(this).hide();
                    };
                });

                $(triggerOpen).ready(function () {
                    $(triggerClose)[0].onclick = function() { // перезапишет существующий обработчик
                        $(element).removeClass(classMore).addClass(classLess);
                        $(triggerOpen).show();
                        $(this).hide();
                    };
                });

                let observer = new MutationObserver(function(mutations) {
                    getCountLines();

                    if(lines > countLines){
                        $(element).removeClass(classMore).addClass(classLess);
                        $(triggerOpen).show();
                        $(triggerClose).hide();
                    }
                    else{
                        $(element).removeClass(classLess).addClass(classMore);
                        $(triggerOpen).hide();
                        $(triggerClose).hide();
                    }
                });
                observer.observe(element[0], { childList: true, characterData: true });

                function getCountLines() {
                    $(element).removeClass(classLess).addClass(classMore);

                    const divHeight = element[0].offsetHeight;
                    const lineHeight = Math.floor(parseFloat($(element).css('line-height').replace('px','')));
                    lines = divHeight / lineHeight;
                }
            }
        };
    }]);

})(window.autoparusApp);