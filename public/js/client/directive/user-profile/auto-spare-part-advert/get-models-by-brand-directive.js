(function(autoparusApp) {
    'use strict';

    autoparusApp.directive("getModelsByBrand",['ProviderCarsData', function(ProviderCarsData){
        return{
            restrict: 'A',
            link: function(scope, element, attrs)
            {
                $("body").on("change", attrs.brandSelect, function(ev){
                    let listModels = $(attrs.listModels);
                    let modelPrototype = $(attrs.containerModel).html();
                    let legendElement = $(attrs.legendElement);
                    let val = $(this).val();
                    let self = this;

                    ProviderCarsData.getModels(val).then(function(models){
                        models = removeModelsWithoutIdAndEmptyName(models);

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

                function removeModelsWithoutIdAndEmptyName(models){
                    for(let name in models){
                        console.log(name);
                        console.log(models[name]);
                        if(!models[name] || !name.trim()){
                            delete models[name];
                        }
                    }

                    return models;
                }
            }
        };
    }]);

})(window.autoparusApp);