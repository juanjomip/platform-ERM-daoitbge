'use strict';
/**
* @ngdoc overview
* @name taxiApp
* @description
* # taxiApp
*
* Main module of the application.
*/
var app = angular

    .module('app', [
        'ngAnimate',
        'ngCookies',
        'ngResource',
        'ui.router',
        'ngSanitize',
        'ngTouch',
        'ui.bootstrap',
        'mgcrea.ngStrap',
        'ng-fusioncharts'                        
    ]);


app.run(function ($rootScope, $window, $http, $modal) {
});


app.config(function ($stateProvider, $urlRouterProvider, $locationProvider) {    
    $locationProvider.html5Mode(true);    
    $stateProvider   
    .state('main-map', {
        url: '/main-map',
        templateUrl: '/views/main-map.html',
        controller: 'mainMapCtrl'
    })
    .state('charts', {
        url: '/charts',
        templateUrl: '/views/charts.html',
        controller: 'chartsCtrl'
    })    
});


