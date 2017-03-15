'use strict';

app.controller('mainMapCtrl', function ($rootScope, $scope, $http, $state) {
    
    // Dates for Querys
    $scope.queryData = {
        'maxDate':'2017-12-31',
        'minDate': '2016-01-01',        
    }   

    // Lists of objects
    $scope.polygons = [];
    $scope.samples = [];
    $scope.cells = [];

    // Detail Data
    $scope.polygon = [];
    $scope.cell = [];

    // Page Status
    $scope.pageStatus = undefined;

    // NG-IF Functions
    $scope.polygonsList = function() {
        if($scope.pageStatus == 'polygons')
            return true;
        else
            return false;
    } 

    $scope.cellsList = function() {
        if($scope.pageStatus == 'cells')
            return true;
        else
            return false;
    } 

    $scope.samplesList = function() {
        if($scope.pageStatus == 'samples')
            return true;
        else
            return false;
    }

    // Main Map
    $scope.santiago = new google.maps.LatLng(-33.465, 289.35)
    $rootScope.map = new google.maps.Map(document.getElementById('map'), {
        zoom: 11,
        center: $scope.santiago,
        panControl: true,
        zoomControl: true,
        navigationControl: true,
        mapTypeControl: true,
        scaleControl: true,
        draggable: true
    });    

    // Create markers.
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
            map: null                     
        });

        var infowindow = new google.maps.InfoWindow({
            content: 'value : ' + sample.value + ' - date : ' + sample.date
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

    $scope.deleteSamples = function(samples) {        
        for (var i = 0; i < samples.length; i++) {            
            $scope.deleteSample(samples[i]);
        }  
        $scope.samples = [];      
    }

    $scope.deleteSample =  function(sample) {
        if(sample.marker != undefined)
            sample.marker.setMap(null);
    }

    $scope.deleteCells = function(cells) {        
        for (var i = 0; i < cells.length; i++) {            
            $scope.deleteCell(cells[i]);
        }  
        $scope.cells = [];      
    }

    $scope.deleteCell =  function(cell) {
        if(cell.polygon != undefined)
            cell.polygon.setMap(null);
    }

    $scope.deleteCommunes = function(comunes) {        
        for (var i = 0; i < comunes.length; i++) {            
            $scope.deleteCommune(comunes[i]);
        }  
        $scope.communes = [];      
    }

    $scope.deleteCommune =  function(commune) {
        if(commune.polygon != undefined)
            commune.polygon.setMap(null);
        if(commune.cells != undefined){
            $scope.deleteCells(commune.cells);
        }
    }

    // create markers from samples.
    $scope.drawCommunes = function(comunes) {        
        for (var i = 0; i < comunes.length; i++) {            
            $scope.drawCommune(comunes[i], $rootScope.map);
        }        
    }

    $scope.drawCommune = function(commune, map) {        
        if(commune.polygon != undefined) {
            commune.polygon.setMap(map);
        }      
        var color = '#6E6E6E';        
        if(commune.value != null && commune.path != undefined) {            
            
            if(commune.value <= 25 && commune.value > 0) {                
                color = '#3ADF00';
            }
            else if(commune.value > 25 && commune.value <= 50) {                
                color = '#F7FE2E';
            }
            else if(commune.value > 50 && commune.value <= 75) {                                
                color = '#FF8000';
            }
            else if(commune.value > 75) {                
                color = '#FF0000';
            }
            else {
                color = '#000000';                
            }
        } else {
            color = '#6E6E6E';
        }
        //console.log(commune.path);

        var polygon = new google.maps.Polygon({
            paths: commune.path,
            strokeColor: color,
            strokeOpacity: 0.5,
            strokeWeight: 1,
            fillColor: color,
            fillOpacity: 0.3
        });

        polygon.setMap(null);
        
        /*google.maps.event.addListener(polygon, 'click', function (event) {
            $scope.selectPolygonFromMap(commune);
        });

        google.maps.event.addListener(polygon, 'mouseover', function (event) {            
            $scope.communeMouseOver(commune);
        });  

        google.maps.event.addListener(polygon, 'mouseout', function (event) {            
            $scope.communeMouseOut(commune);
        });
        */  

        commune.polygon = polygon;
    }

    $scope.communeMouseOver = function(commune) {
        commune.polygon.setOptions({
            strokeOpacity: 1,
            strokeWeight: 2,
        });
    }

    $scope.communeMouseOut = function(commune) {
        commune.polygon.setOptions({
            strokeOpacity: 0.5,
            strokeWeight: 0.5,
        });
    }

    $scope.selectCommune = function(commune) {
        $scope.queryData.commune_id = commune.id;
        //console.log(commune.name);                     
        var latLng = new google.maps.LatLng(commune.center_lat,commune.center_lng)
        $rootScope.map.panTo(latLng);
        //$scope.showCommune(commune, $rootScope.map);
        $scope.hideCommunes($scope.communes);
        $rootScope.map.setZoom(13);
        $scope.query();
        //$scope.$digest();
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
    }

    $scope.hideSamples = function(samples) {        
        for (var i = 0; i < samples.length; i++) {            
            samples[i].marker.setMap(null);                     
        }        
    }

    $scope.showSamples = function(samples) {        
        for (var i = 0; i < samples.length; i++) {            
            samples[i].marker.setMap($rootScope.map);                     
        }        
    }

    $scope.hideCommune = function(commune) {
        commune.polygon.setMap(null);
        if(commune.cells != undefined) {
            hideCells(commune.cells);
        }
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
        ////console.log(cell.path);
        cell.path.push(cell.path[0]); 

        var color = '#000000';
        if(cell.value != null && cell.path != undefined) {
            //console.log(cell);
            if(cell.value <= 25)
                color = '#3ADF00';
            else if(cell.value > 25 && cell.value <= 50)
                color = '#F7FE2E';
            else if(cell.value > 50 && cell.value <= 75)
                color = '#FF8000';
            else if(cell.value > 75)
                color = '#FF0000';
            else
                color = '#000000';
        } else {
            color = '#000000';
        }       

        var polygon = new google.maps.Polygon({
            paths: cellPath,
            strokeColor: color,
            strokeOpacity: 0.5,
            strokeWeight: 1,
            fillColor: color,
            fillOpacity: 0.3
        });
        polygon.setMap(null);

        /*google.maps.event.addListener(polygon, 'click', function (event) {
            $scope.selectCell(cell);
        });

        google.maps.event.addListener(polygon, 'mouseover', function (event) {            
            $scope.cellMouseOver(cell);
        });  

        google.maps.event.addListener(polygon, 'mouseout', function (event) {            
            $scope.cellMouseOut(cell);
        });
        */

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
    }

    $scope.selectCell = function(cell){       
        var latLng = new google.maps.LatLng(cell.center_lat, cell.center_lng);        
        $rootScope.map.setZoom(17);
        $rootScope.map.panTo(latLng);
    } 

    $scope.getCommunes = function () {
        $http.get('/api/communes/' + $scope.queryData.minDate  + '/' + $scope.queryData.maxDate)
            .then(function(response) {
                //console.log(response);               
                $scope.communesList = response.data.communes; 
                //$scope.communes = response.data.communes;                                              
                //$scope.drawCommunes($scope.communes);                      

            },
            function error(response) {
                //console.log(response);
            }
        );         
    }

    // center in bounds
    $scope.panToPolygon = function(path) {
        var bounds = new google.maps.LatLngBounds();
        for (var i = 0; i < path.length; i++) {         
            var position = new google.maps.LatLng(path[i].lat, path[i].lng)         
            bounds.extend(position);         
        }
        $rootScope.map.fitBounds(bounds);
    }

    // QUERYS ***************************************************************************************************/
    
    // ON MAP START.
    $scope.getPolygons = function () {
        $http.get('/api/polygons/' + $scope.queryData.minDate  + '/' + $scope.queryData.maxDate)
            .then(function(response) {     
                // Delete current data.  
                $scope.deleteCommune($scope.polygon);         
                $scope.deleteCommunes($scope.polygons);
                $scope.deleteCells($scope.cells);
                $scope.deleteCell($scope.cell);
                $scope.deleteSamples($scope.samples);
                $scope.deleteCommune($scope.polygon);

                // New Data.
                $scope.polygons = response.data.data;                
                $scope.drawCommunes($scope.polygons);
                if($scope.watch.polygons) {
                    $scope.showCommunes($scope.polygons);
                }
                $scope.watch.polygon = false;
                $scope.watch.polygons = true;
                $scope.pageStatus = 'polygons';

                console.log(response);
                $rootScope.map.setCenter($scope.santiago);
                $rootScope.map.setZoom(11);
                $scope.updateChart(response.data.data);
            },
            function error(response) {
                console.log(response);
            }
        );         
    }  

    // ON SELECT A ROW OF POLYGONS TABLE.
    $scope.getPolygon = function (id) {
        $http.get('/api/polygon/' + $scope.queryData.minDate  + '/' + $scope.queryData.maxDate + '/' + id)
            .then(function(response) {

                $scope.deleteCommune($scope.polygon); 
                $scope.deleteCommunes($scope.polygons);
                $scope.deleteCells($scope.cells);
                $scope.deleteCell($scope.cell);
                $scope.deleteSamples($scope.samples);

                $scope.polygon = response.data.data;
                $scope.drawCommune($scope.polygon);
                if($scope.watch.polygon) {
                    $scope.showCommune($scope.polygon, $rootScope.map);
                }                
                $scope.watch.polygon = true;
                $scope.watch.polygons = false;
                $scope.panToPolygon($scope.polygon.path);
                $scope.getCells($scope.polygon.id);
                $scope.pageStatus = 'cells';
                $scope.updateChart(response.data.data);               
            },
            function error(response) {
                console.log(response);
            }
        );         
    }

    $scope.getCells = function (id) {        
        $http.get('/api/cells/' + $scope.queryData.minDate  + '/' + $scope.queryData.maxDate + '/' + id)
            .then(function(response) {               

                $scope.cells = response.data.data;               
                $scope.drawCells($scope.cells);  
                if($scope.watch.cells) {
                    $scope.showCells($scope.cells);
                }              
                $scope.watch.cells = true;                         
            },
            function error(response) {
                console.log(response);
            }
        );         
    }

    $scope.getCell = function (id) {               
        $http.get('/api/cell/' + $scope.queryData.minDate  + '/' + $scope.queryData.maxDate + '/' + id)
            .then(function(response) {         

                //$scope.deleteCommune($scope.polygon); 
                $scope.deleteCommunes($scope.polygons);
                $scope.deleteCells($scope.cells);
                $scope.deleteCell($scope.cell);
                $scope.deleteSamples($scope.samples);

                $scope.cell = response.data.data; 
                $scope.drawCell($scope.cell, $rootScope.map);
                $scope.panToPolygon($scope.cell.path);                 
                $scope.getSamples($scope.cell.id);
                if($scope.watch.cell) {
                    $scope.showCell($scope.cell, $rootScope.map);
                } 
                $scope.watch.cell = true;                         
                                
            },
            function error(response) {
                console.log(response);
            }
        );         
    }

    $scope.getSamples = function (id) {               
        $http.get('/api/samples/' + $scope.queryData.minDate  + '/' + $scope.queryData.maxDate + '/' + id)
            .then(function(response) {                           
                
                $scope.samples = response.data.data;    
                $scope.createMarkers($scope.samples);
                if($scope.watch.samples) {
                    $scope.showSamples($scope.samples);
                } 
                $scope.watch.samples = true;
                $scope.deleteCells($scope.cells);
                $scope.watch.cells = false;
                //$scope.watch.cells = true;
                $scope.pageStatus = 'samples';                             
            },
            function error(response) {
                console.log(response);
            }
        );         
    }

    $scope.getReport = function() {
        $http.get('/api/report/' + $scope.pageStatus + '/' + $scope.queryData.minDate  + '/' + $scope.queryData.maxDate + '/' + $scope.polygon.id)
            .then(function(response) {  
                console.log(response);                                      
            },
            function error(response) {
                console.log(response);
            }
        );       
    }

    $scope.updateChart = function(data) {        
        $scope.attrs.caption = '';          
        $scope.dataset = [{"seriesname": "","data": []}];
        $scope.categories[0].category = []; 
        if($scope.pageStatus == 'polygons') {  
            $scope.attrs.caption = 'Resumen Comunas desde: ' + $scope.queryData.minDate + ' hasta: ' + $scope.queryData.maxDate;                  
            for (var i = 0; i < $scope.polygons.length; i++) {                         
                $scope.dataset[0].seriesname = 'Medición'; 
                $scope.dataset[0].data.push({value: $scope.polygons[i].value});                      
                $scope.categories[0].category.push({label: $scope.polygons[i].name});          
            }
        }
        else if($scope.pageStatus == 'cells') {           
            $scope.attrs.caption = 'Resumen histórico' + $scope.polygon.name + ' desde: ' + $scope.queryData.minDate + ' hasta: ' + $scope.queryData.maxDate;                  
            for (var i = 0; i < $scope.polygon.measurements.length; i++) {                         
                $scope.dataset[0].seriesname = 'Medición'; 
                $scope.dataset[0].data.push({value: $scope.polygon.measurements[i].value});                      
                $scope.categories[0].category.push({label: $scope.polygon.measurements[i].date});          
            }
        } else {
            $scope.attrs.caption = ''; 
        }           
    }

    $scope.getBack = function() {
        if($scope.pageStatus == 'polygons') {
            
        } else if($scope.pageStatus == 'cells') {
            $scope.getPolygons();
        } else if($scope.pageStatus == 'samples') {
           $scope.getPolygon($scope.polygon.id)
        } else {
            console.log('do nothing');
        }
    }

    // Init Functions.
  
    $scope.getPolygons();  

    $scope.watch = {
        currentCommune: false,
        polygons: false,
        cells: false,
        samples: false,
        zones: false     
    }

    $scope.$watch('watch.polygons', function(newValue, oldValue) {
        //console.log('polygons watch');
        if(newValue) {            
            $scope.showCommunes($scope.polygons);
        } else {
            if($scope.polygons.length > 0)
                $scope.hideCommunes($scope.polygons);
        }
    });

    $scope.$watch('watch.polygon', function(newValue, oldValue) {        
        if(newValue) {
            $scope.showCommune($scope.polygon, $rootScope.map);
        } else {
            if($scope.polygon.polygon != undefined)
                $scope.hideCommune($scope.polygon);
        }
    });

    $scope.$watch('watch.cells', function(newValue, oldValue) {        
        if(newValue) {
            $scope.showCells($scope.cells, $rootScope.map);
        } else {
            $scope.hideCells($scope.cells);
        }
    });

    $scope.$watch('watch.cell', function(newValue, oldValue) {        
        if(newValue) {
            console.log($scope.cell);
            $scope.showCell($scope.cell, $rootScope.map);
        } else {
            $scope.hideCell($scope.cell);
        }
    });

    $scope.$watch('watch.samples', function(newValue, oldValue) {        
        if(newValue) {
            $scope.showSamples($scope.samples, $rootScope.map);
        } else {
            $scope.hideSamples($scope.samples);
        }
    });

    $scope.attrs = {
        "caption": "Resumen",
        "numberprefix": "",
        "plotgradientcolor": "",
        "bgcolor": "FFFFFF",
        "showalternatehgridcolor": "0",
        "divlinecolor": "CCCCCC",
        "showvalues": "0",
        "showcanvasborder": "0",
        "canvasborderalpha": "0",
        "canvasbordercolor": "CCCCCC",
        "canvasborderthickness": "1",
        "yaxismaxvalue": "100",
        "captionpadding": "30",
        "linethickness": "3",
        "yaxisvaluespadding": "15",
        "legendshadow": "0",
        "legendborderalpha": "0",
        "palettecolors":  "#000000,#008ee4,#33bdda,#e44a00,#6baa01,#583e78",
        "showborder": "0"
    };

    console.log($scope.attrs);
    $scope.categories = [
        {
            "category": [                
                
            ]
        }
    ];

    $scope.dataset = [
        {
            "seriesname": "",
            "data": []
        }        
    ];


});

