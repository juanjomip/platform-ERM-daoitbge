'use strict';

app.controller('MainCtrl', function ($rootScope, $scope, $http) {
    console.log('main-controller');  
    var santiago = new google.maps.LatLng(-33.465, 289.35)
    $rootScope.map = new google.maps.Map(document.getElementById('map'), {
        zoom: 12,
        center: santiago,
        panControl: false,
        zoomControl: false,
        navigationControl: false,
        mapTypeControl: false,
        scaleControl: false,
        draggable: false
    });

    // get all samples from backend.
    $scope.getSamples = function () {
        $http.get('/api/samples')
            .then(function(response) {           	
                $scope.createMarkers(response.data.samples);          
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
        var position = new google.maps.LatLng(-33.465, 289.35)
        var marker = new google.maps.Marker({
        position: position,
            map: map,            
        });

        var infowindow = new google.maps.InfoWindow({
            content: 'value : ' + sample.value
        });
  
        marker.addListener('click', function() {
            infowindow.open(map, marker);
        });
    }

    $scope.getSamples();
});

