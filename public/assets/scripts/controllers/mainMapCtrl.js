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
                $scope.cells = response.data.cells;
                $scope.drawCells($scope.cells);
                $scope.createMarkers($scope.samples);                
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
            icon: {
                path: google.maps.SymbolPath.CIRCLE,
                scale: 4
            },            
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

    // draw cells.
    $scope.drawCells = function(cells) {
        for (var i = 0; i < cells.length; i++) {
            $scope.drawCell(cells[i], $rootScope.map);
        } 
    }

    // draw cell.
    $scope.drawCell = function(cell, map) {

        console.log(cell);
        
        var cellPath = [
            {lat: cell.bottom_left_lat, lng: cell.bottom_left_lng},
            {lat: cell.top_left_lat, lng: cell.top_left_lng},
            {lat: cell.top_right_lat, lng: cell.top_right_lng},
            {lat: cell.bottom_right_lat, lng: cell.bottom_right_lng},
            {lat: cell.bottom_left_lat, lng: cell.bottom_left_lng}
        ];

        console.log(cellPath);

        var polygon = new google.maps.Polygon({
            paths: cellPath,
            strokeColor: '#FF0000',
            strokeOpacity: 0.2,
            strokeWeight: 0,
            fillColor: '#FF0000',
            fillOpacity: 0.2
        });
        polygon.setMap(map);
        cell.polygon = polygon;
    }


    $scope.getSamples();

});

