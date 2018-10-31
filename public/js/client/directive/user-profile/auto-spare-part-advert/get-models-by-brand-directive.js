(function(autoparusApp) {
    'use strict';

    autoparusApp.directive("getModelsByBrand",['ProviderCarsData', function(ProviderCarsData){
        return{
            restrict: 'A',
            link: function(scope, element, attrs)
            {
                let brandSelect = $(attrs.brandSelect);
                let modelPrototype = $(attrs.containerModel).html();
                let listModels = $(attrs.listModels);
                let legendElement = $(attrs.legendElement);

                brandSelect.change(function(ev){
                    let val = $(this).val();

                    if(parseInt(val) !== 0) {
                        legendElement.find("span").html($(this).find("option[value=" + val + "]").html());
                        legendElement.show();
                    }
                    else{
                        legendElement.hide();
                        listModels.html("");
                    }

                    ProviderCarsData.getModels(val).then(function(models){
                        listModels.html("");
                        let index = 0;

                        for(let name in models){
                            if(!models[name]){
                                continue;
                            }

                            let model = $(modelPrototype.replace(/(_\_index\_\_)/g, index++));
                            model.find("input").val(models[name]);
                            model.find("label").html(name);

                            listModels.append(model)
                        }
                    });
                });

            }
        };
    }]);

})(window.autoparusApp);