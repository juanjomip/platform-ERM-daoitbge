app.directive('autocomplete', function(OpcionesMarker) { 
    return {
        restrict: 'E',
        replace: true,
        transclude: true,
        template: '<input name="autocomplete" type="text"/>',
        link: function(scope, element, attrs) {
            scope.$watch(attrs.list, function(value) {
                element.autocomplete({
                    source: value,
                    select: function(event, ui) {
                        scope[attrs.selection] = ui.item.value;
                        scope.$apply();
                        console.log(ui.item.value);
                        OpcionesMarker.searchDriver(ui.item.value);
                    }
                });
            });
        }
    }
});

app.directive('rutautocomplete', function(OpcionesMarker, $rootScope, ConductorService) { 
    return {
        restrict: 'E',
        replace: true,
        transclude: true,
        template: '<input name="autocomplete" type="text"/>',
        link: function(scope, element, attrs) {                            
            scope.$watch(attrs.list, function(value) {                                                 
                element.autocomplete({                                  
                    source: $rootScope.availableRuts,
                    select: function(event, ui) {
                        scope[attrs.selection] = ui.item.value;
                        console.log(value);
                        $rootScope.cliente = ui.item.data;
                        scope.$apply();
                        console.log(ui.item.value);
                        console.log($rootScope.cliente);
                        //OpcionesMarker.searchDriver(ui.item.value);
                    }  
                });
            });
        }
    }
});