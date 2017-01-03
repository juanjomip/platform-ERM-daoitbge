'use strict';

/**
 * @ngdoc function
 * @name taxiApp.directive:stRatio
 * @Table td width
 * # stRAtio
 * Directive of the taxiApp
 */

angular.module('taxiApp')
.directive('stRatio',function(){
    return {
        link:function(scope, element, attr){
            var ratio=+(attr.stRatio);
        
            element.css('width',ratio+'%');
        
        }
    };
});