'use strict';

/**
 * @ngdoc function
 * @name taxiApp.directive:resize
 * @description
 * # Resize
 * Directive of the taxiApp
 */

angular.module('taxiApp')
.directive('resize', function ($window) {
	return function (scope, element, state, rootScope){
		var w = angular.element($window);
		scope.getWindowDimensions = function () {
			return { 'h': w.height()};
		};
		scope.$watch(scope.getWindowDimensions, function (newValue, oldValue){

			scope.windowHeight = newValue.h;
            scope.windowWidth = newValue.w;

            scope.listaSinAsignacion = function () {
				return { 
                    'height': (newValue.h - 96) + 'px',
                    'overflow-y': 'overlay',
                    'overflow-x': 'hidden'
                };
			};

			scope.listaAsignadas = function () {
				return { 
                    'height': (newValue.h - 153) + 'px',
                    'overflow-y': 'overlay',
                    'overflow-x': 'hidden'
                };
			};

			scope.ordersMap = function () {
				return { 
                    'height': (newValue.h - 165) + 'px'
                };
			};

			scope.orderForm = function () {
				return { 
                    'height': (newValue.h - 150) + 'px'
                };
			};

			scope.orderList = function () {
				return { 
                    'height': (newValue.h - 215) + 'px',
                    'overflow-y': 'overlay'
                };
			};

			scope.staticForm = function () {
				return { 
                    'height': (newValue.h - 150) + 'px'
                };
			};

			scope.staticElementFull = function () {
				return { 
                    'height': (newValue.h - 55) + 'px'
                };
			};

			scope.staticFormHead = function () {
				return { 
                    'height': (newValue.h - 116) + 'px'
                };
			};

			scope.scrollFormContent = function () {
				return { 
                    'height': (newValue.h - 250) + 'px'
                };
			};

			scope.staticFormTabs = function () {
				return { 
                    'height': (newValue.h - 213) + 'px'
                };
			};

			scope.auditResultList = function () {
				return { 
                    'height': (newValue.h - 170) + 'px',
                    'overflow-x': 'hidden'
                };
			};

			scope.tabPlusList = function () {
				return { 
                    'height': (newValue.h - 95) + 'px',
                    'overflow-x': 'hidden'
                };
			};
            
		}, true);
	
		w.bind('resize', function () {
			scope.$apply();
		});
	}
});
