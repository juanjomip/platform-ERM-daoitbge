'use strict';

app.controller('mainMapCtrl', function ($rootScope, $scope, $http, $state) {
    
     $scope.samples = [];

    var santiago = new google.maps.LatLng(-33.465, 289.35)
    $rootScope.map = new google.maps.Map(document.getElementById('map'), {
        zoom: 12,
        center: santiago,
        panControl: true,
        zoomControl: true,
        navigationControl: true,
        mapTypeControl: true,
        scaleControl: true,
        draggable: true
    });

    // get all samples from backend.
    $scope.getSamples = function () {
        $http.get('/api/samples')
            .then(function(response) {                 
                $scope.samples = response.data.samples;
                $scope.createMarkers($scope.samples); 
                for (var i = 0; $scope.samples.length > i; i++) {
                   console.log($scope.samples[i]);      
                }  
            },
            function error(response) {
                console.log(response);
            }
        );         
    }

    // create markers from samples.
    $scope.createMarkers = function(samples) {        
        for (var i = 0; i < samples.length; i++) {
            $scope.createMarker(samples[i], $rootScope.map);
        }        
    }

    // create marker from a sample.
    $scope.createMarker = function(sample, map) {          
        var position = new google.maps.LatLng(sample.lat, sample.lng)
        var marker = new google.maps.Marker({
        position: position,
            map: map,            
        });

        var infowindow = new google.maps.InfoWindow({
            content: 'value : ' + sample.value + ' - date : ' + sample.datetime
        });
  
        marker.addListener('click', function() {
            infowindow.open(map, marker);
        });

        marker.infowindow = infowindow;        
        sample.marker = marker;       
    } 



    $scope.getSamples();

});

