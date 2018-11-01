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
                    let self = this;

                    ProviderCarsData.getModels(val).then(function(models){
                        models = removeModelsWithoutId(models);

                        if(parseInt(val) !== 0 && Object.values(models).length) {
                            legendElement.find("span.second-span").html($(self).find("option[value=" + val + "]").html());
                            legendElement.show();
                        }
                        else{
                            legendElement.hide();
                            listModels.html("");
                        }

                        listModels.html("");
                        let index = 0;

                        for(let name in models){
                            let model = $(modelPrototype.replace(/(_\_index\_\_)/g, index++));
                            model.find("input").val(models[name]).addClass("model-checkbox");
                            model.find("label").html(name);

                            listModels.append(model)
                        }
                    });
                });

                function removeModelsWithoutId(models){
                    for(let name in models){
                        if(!models[name]){
                            delete models[name];
                        }
                    }

                    return models;
                }
            }
        };
    }]);

})(window.autoparusApp);