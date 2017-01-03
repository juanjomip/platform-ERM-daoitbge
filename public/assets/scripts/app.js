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
        'mgcrea.ngStrap'                        
    ]);


app.run(function ($rootScope, $window, $http, $modal) {
});


app.config(function ($stateProvider, $urlRouterProvider, $locationProvider) {    
    $locationProvider.html5Mode(true);
});


