<!doctype html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<title>Index</title>
	<style>
       #map {
        height: 800px;
        width: 50%;
        float: right;
       }
    </style>
	
</head>
<body ng-app="app" ng-controller="MainCtrl">
	

    <div class="welcome">		
		<h1>Platform-ERM-Prototype</h1>		
	</div>

    <div ui-view></div>

	<script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyAnpCoCt1diZedDOsnoEdX4tGYZ8nCnQUI&extension=.js"></script>
	<script src="/bower_components/jquery/dist/jquery.min.js"></script>
    <script src="/bower_components/angular/angular.min.js"></script>
    <script src="/bower_components/angular-bootstrap/ui-bootstrap-tpls.min.js"></script>
    <script src="/bower_components/angular-strap/dist/angular-strap.js"></script>
    <script src="/bower_components/angular-strap/dist/angular-strap.tpl.js"></script>
    <script src="/bower_components/angular-resource/angular-resource.min.js"></script>
    <script src="/bower_components/angular-cookies/angular-cookies.min.js"></script>
    <script src="/bower_components/angular-sanitize/angular-sanitize.min.js"></script>
    <script src="/bower_components/angular-animate/angular-animate.min.js"></script>
    <script src="/bower_components/angular-touch/angular-touch.min.js"></script>
    <script src="/bower_components/angular-route/angular-route.min.js"></script>
    <script src="/bower_components/angular-ui-router/release/angular-ui-router.min.js"></script>
    <script src="/vendor/fusioncharts-suite-xt/js/fusioncharts.js"></script>
    <script src="/vendor/angular-fusioncharts.min.js"></script>

    <script src="assets/scripts/app.js"></script>
    <script src="assets/scripts/controllers/mainCtrl.js"></script>
    <script src="assets/scripts/controllers/mainMapCtrl.js"></script>
    <script src="assets/scripts/controllers/chartsCtrl.js"></script>

</body>
</html>
