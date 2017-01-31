'use strict';

app.controller('mainMapCtrl', function ($rootScope, $scope, $http, $state) {
    
    $scope.samples = [];
    $scope.cells = [];
    $scope.communes = [];
    $scope.selectedCommune = {
        name: '',
        value: 28
    };    

    var santiago = new google.maps.LatLng(-33.465, 289.35)
    $rootScope.map = new google.maps.Map(document.getElementById('map'), {
        zoom: 11,
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
                $scope.communes = response.data.communes;
                $scope.drawCells($scope.cells);
                //$scope.createMarkers($scope.samples);                
                $scope.drawCommunes($scope.communes);  
                console.log($scope.cells);            
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


    // create markers from samples.
    $scope.drawCommunes = function(comunes) {        
        for (var i = 0; i < comunes.length; i++) {            
            $scope.drawCommune(comunes[i], $rootScope.map);
        }        
    }

    $scope.drawCommune = function(commune, map) {
        var polygon = new google.maps.Polygon({
            paths: commune.path,
            strokeColor: '#000000',
            strokeOpacity: 0.5,
            strokeWeight: 0.5,
            fillColor: '#000000',
            fillOpacity: 0.1
        });

        polygon.setMap(map);
        
        google.maps.event.addListener(polygon, 'click', function (event) {
            $scope.communeSelect(commune);
        });    

        commune.polygon = polygon;
    }

    $scope.communeSelect = function(commune) {
        $scope.selectedCommune.name = commune.name;          
        var latLng = new google.maps.LatLng(commune.center_lat,commune.center_lng)
        $rootScope.map.panTo(latLng);
        $scope.showCommune(commune, $rootScope.map);
        $scope.hideCommunes($scope.communes, commune);
        $rootScope.map.setZoom(13);
        $scope.$digest();
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

    $scope.hideCommunes = function(communes, exception) {
        for (var i = 0; i < communes.length; i++) {
            if(communes[i].name != exception.name)
                $scope.hideCommune(communes[i]);
        } 
    }

    $scope.hideCommune = function(commune) {
        commune.polygon.setMap(null);
    }

    $scope.showCommunes = function(communes, exception, map) {
        for (var i = 0; i < communes.length; i++) {
            if(communes[i].name != exception.name)
                $scope.showCommune(communes[i], map);
        } 
    }
    
    $scope.showCommune = function(commune, map) {
        commune.polygon.setMap(map);
    }

    // draw cells.
    $scope.drawCells = function(cells) {
        for (var i = 0; i < cells.length; i++) {
            $scope.drawCell(cells[i], $rootScope.map);
        } 
    }    

    // draw cell.
    $scope.drawCell = function(cell, map) {        
        
        var cellPath = [
            {lat: cell.bottom_left_lat, lng: cell.bottom_left_lng},
            {lat: cell.top_left_lat, lng: cell.top_left_lng},
            {lat: cell.top_right_lat, lng: cell.top_right_lng},
            {lat: cell.bottom_right_lat, lng: cell.bottom_right_lng},
            {lat: cell.bottom_left_lat, lng: cell.bottom_left_lng}
        ];        

        var polygon = new google.maps.Polygon({
            paths: cellPath,
            strokeColor: '#000000',
            strokeOpacity: 0.2,
            strokeWeight: 0.5,
            fillColor: '#000000',
            fillOpacity: 0.2
        });
        polygon.setMap(map);
        cell.polygon = polygon;
    }

    

    $scope.getSamples();

});

