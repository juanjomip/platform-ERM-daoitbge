'use strict';

app.controller('mainMapCtrl', function ($rootScope, $scope, $http, $state) {
    
    $scope.samples = [];
    $scope.cells = [];
    $scope.communes = [];     

    $scope.pageStatus = {
        showCommunes: true,
        showCells: true       
    }  

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

    $scope.query = function() {
        console.log($scope.minDate);
        console.log($scope.maxDate);
    }

    // get all samples from backend.
    $scope.getSamples = function () {
        $http.get('/api/samples')
            .then(function(response) {           
                $scope.samples = response.data.samples;
                $scope.cells = response.data.cells;
                
                $scope.communes = response.data.communes;
<<<<<<< HEAD
                $scope.drawCells($scope.cells);
                //$scope.createMarkers($scope.samples);                
                $scope.drawCommunes($scope.communes);                          
=======

                console.log('cells');
                console.log(response.data.cells);

                console.log('markers');
                console.log(response.data.markers);
                $scope.drawCells($scope.cells);
                //$scope.createUTMS(response.data.markers);
                $scope.createMarkers($scope.samples);                
                //$scope.drawCommunes($scope.communes);                          
>>>>>>> 63f52bfd48e7c5c21b22424737ebfa4059608583
            },
            function error(response) {
                console.log(response);
            }
        );         
    }

    $scope.getCommuneCells = function (commune_id) {        
        $http.get('/api/polygoncells/' + commune_id)
            .then(function(response) {
                console.log(response);                 
                $scope.hideCells($scope.cells);
                $scope.cells = response.data.cells;
                $scope.drawCells($scope.cells);                    
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

    $scope.createUTMS = function(samples) {        
        for (var i = 0; i < samples.length; i++) {
            $scope.createUTM(samples[i], $rootScope.map);
        }        
    }

    // create marker from a sample.
    $scope.createUTM = function(sample, map) {          
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

    /* Communes *****************************************************************************************************
    |
    |
    |****************************************************************************************************************/

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
            $scope.selectCommune(commune);
        });

        google.maps.event.addListener(polygon, 'mouseover', function (event) {            
            $scope.communeMouseOver(commune);
        });  

        google.maps.event.addListener(polygon, 'mouseout', function (event) {            
            $scope.communeMouseOut(commune);
        });  

        commune.polygon = polygon;
    }

    $scope.communeMouseOver = function(commune) {
        commune.polygon.setOptions({
            strokeOpacity: 1,
            strokeWeight: 0.5,
        });
    }

    $scope.communeMouseOut = function(commune) {
        commune.polygon.setOptions({
            strokeOpacity: 0.5,
            strokeWeight: 0.5,
        });
    }

    $scope.selectCommune = function(commune) {
        $scope.getCommuneCells(commune.id);               
        var latLng = new google.maps.LatLng(commune.center_lat,commune.center_lng)
        $rootScope.map.panTo(latLng);
        $scope.showCommune(commune, $rootScope.map);
        $scope.hideCommunes($scope.communes, commune);
        $rootScope.map.setZoom(13);
        $scope.$digest();
    }    

    $scope.hideCommunes = function(communes, exception) {        
        if(exception != undefined) {
            for (var i = 0; i < communes.length; i++) {            
                if(communes[i].name != exception.name)
                    $scope.hideCommune(communes[i], map);                     
            }
        } else {
            for (var i = 0; i < communes.length; i++) {            
                $scope.hideCommune(communes[i], map);                     
            }
        }
        $scope.pageStatus.showCommunes = false;
    }

    $scope.hideCommune = function(commune) {
        commune.polygon.setMap(null);
    }

    $scope.showCommunes = function(communes, exception) {
        var map = $rootScope.map;       
        if(exception != undefined) {
            for (var i = 0; i < communes.length; i++) {            
                if(communes[i].name != exception.name)
                    $scope.showCommune(communes[i], map);                     
            }
        } else {
            for (var i = 0; i < communes.length; i++) {            
                $scope.showCommune(communes[i], map);                     
            }
        }
        $scope.pageStatus.showCommunes = true;
    }
    
    $scope.showCommune = function(commune, map) {
        commune.polygon.setMap(map);
    }

    /* Cells *****************************************************************************************************
    |
    |
    |****************************************************************************************************************/

    // draw cells.
    $scope.drawCells = function(cells) {
        for (var i = 0; i < cells.length; i++) {
            $scope.drawCell(cells[i], $rootScope.map);
        } 
    }    

    // draw cell.
    $scope.drawCell = function(cell, map) {  
        var cellPath = cell.path;
        console.log(cell.path);
        cell.path.push(cell.path[0]);        

        var polygon = new google.maps.Polygon({
            paths: cellPath,
            strokeColor: '#000000',
            strokeOpacity: 0.2,
            strokeWeight: 0.5,
            fillColor: '#000000',
            fillOpacity: 0.2
        });
        polygon.setMap(map);

        google.maps.event.addListener(polygon, 'click', function (event) {
            $scope.selectCell(cell);
        });

        google.maps.event.addListener(polygon, 'mouseover', function (event) {            
            $scope.cellMouseOver(cell);
        });  

        google.maps.event.addListener(polygon, 'mouseout', function (event) {            
            $scope.cellMouseOut(cell);
        });

        cell.polygon = polygon;
    }

    $scope.cellMouseOver = function(cell) {
        cell.polygon.setOptions({
            strokeOpacity: 1,
            strokeWeight: 0.5,
        });
    }

    $scope.cellMouseOut = function(cell) {
        cell.polygon.setOptions({
            strokeOpacity: 0.2,
            strokeWeight: 0.5,
        });
    }

    $scope.showCell = function(cell, map) {
        if(cell.polygon != undefined)
            cell.polygon.setMap(map);
    } 

    $scope.showCells = function(cells, exception) {
        var map = $rootScope.map;       
        if(exception != undefined) {
            for (var i = 0; i < cells.length; i++) {            
                if(cells[i].id != exception.id)
                    $scope.showCommune(cells[i], map);                     
            }
        } else {
            for (var i = 0; i < cells.length; i++) {            
                $scope.showCommune(cells[i], map);                     
            }
        }
        $scope.pageStatus.showCells = true;
    }    

    $scope.hideCell = function(cell) {
        if(cell.polygon != undefined)
            cell.polygon.setMap(null);
    }

    $scope.hideCells = function(cells, exception) {        
        if(exception != undefined) {
            for (var i = 0; i < cells.length; i++) {            
                if(cells[i].id != exception.id)
                    $scope.hideCell(cells[i], map);                     
            }
        } else {
            for (var i = 0; i < cells.length; i++) {            
                $scope.hideCell(cells[i], map);                     
            }
        }
        $scope.pageStatus.showCells = false;
    }

    $scope.selectCell = function(cell){       
        var latLng = new google.maps.LatLng(cell.center_lat, cell.center_lng);        
        $rootScope.map.setZoom(17);
        $rootScope.map.panTo(latLng);
    } 

    /* Init *****************************************************************************************************
    |
    |
    |****************************************************************************************************************/

    $scope.getSamples();

});

