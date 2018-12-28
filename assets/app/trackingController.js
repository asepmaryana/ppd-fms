'use strict';
angular.module('app.controller', ['app.constant', 'ngCookies'])
.controller('TrackingController', ['$rootScope','$scope','$compile','$state','$http','$cookies','$interval', function ($rootScope,$scope,$compile,$state,$http,$cookies,$interval){
	if($cookies.token == null) $state.go('signin');
	$rootScope.$broadcast('tracking-enabled', {});
	$rootScope.busMarkers 		= [];
	$rootScope.stationMarkers 	= [];
	$rootScope.bus	= [];
	$scope.station	= null;
	$scope.busDisplayed = false;
	$scope.stationDisplayed = false;
	$scope.busInfo	= function(bid){
		$http.get(BASE_URL+'/api/bus/info/'+bid).success(function(data){
			$rootScope.bus	= data;
		});
	}
	$scope.stationInfo	= function(sid){
		$http.get(BASE_URL+'/api/station/info/'+sid).success(function(data){
			$scope.station	= data;
		});
	}
	$scope.hideBus	= function(){
		$scope.busDisplayed = false;
	}
	$scope.hideStation	= function(){
		$scope.stationDisplayed = false;
	}
	var getBusIcon	= function(info){
		var img = info.icon;
		info.icon	= 'bus_';
		if(info.alarm_status_id == '2')	info.icon += 'a';
		if(info.operation_status_id == '1' && info.alarm_status_id != '2') info.icon += 'o';
		if(info.icon == null) info.icon += 'y';
		else info.icon += img.substring(4,5);
		info.icon += '0.png';
		
		return BASE_URL+'/assets/images/'+info.icon;
	}
	var getStationIcon	= function(station){
		station.icon	= 'inv';
		if(station.station_type_id == '2')	station.icon += '_f';
		station.icon += '.png';
		return BASE_URL+'/assets/images/'+station.icon;
	}
	var buildBusContent	= function(info){
		var marker	= {content:''};
		
		//speed
        if(info.speed == null) {
        	info.speed = '0';
        	info.speed_color = '';
        }
        else {
        	if(info.max_speed == null) info.speed_color = '';
        	else if(Number(info.speed) > Number(info.max_speed)) info.speed_color = 'color:#FF0000;';
        	else info.speed_color = 'color:#3BA549;';
        }
        
        //status
        if(info.operation_status_id == '1') 	 info.status_color = 'color:#0072BC;';
        else if(info.operation_status_id == '2') info.status_color = 'color:#000000;';
        else info.status_color = 'color:#A4A4A4;';
        
        marker.content	= '<table border="1" cellpadding="1" cellspacing="1" width="300px" bgcolor="#92ABB1" style="border-collapse: collapse; border:1px solid #a4a4a4">';
        	marker.content	+= '<tr height="20px">';
        		marker.content	+= '<td bgcolor="#DAE8EB" align="center" width="30%">Route</td>';
        		marker.content	+= '<td bgcolor=#FFFFFF width="40%" style="padding-left:4px">'+info.route_line_code+'</td>';
        		marker.content	+= '<td style="padding-left:4px; padding-right:4px" align="right" rowspan="4" bgcolor="#FFFFFF">';
        			marker.content	+= '<table border="0" cellpadding="0" cellspacing="0">';
        				marker.content	+= '<tr>';
        					marker.content	+= '<td>';
        						marker.content	+= '<a href="#/app/tracking" ng-click="showCurrentBus()"><img src="assets/images/btn_p1.gif" border="0"></a>';
        					marker.content	+= '</td>';
        				marker.content	+= '</tr>';
        				marker.content	+= '<tr>';
    						marker.content	+= '<td>';
    							marker.content	+= '<a href="#/app/bus/driving/'+info.id+'"><img src="assets/images/btn_p2.gif" border="0"></a>';
    						marker.content	+= '</td>';
    					marker.content	+= '</tr>';
    					marker.content	+= '<tr>';
    						marker.content	+= '<td>';
    							marker.content	+= '<a href="#/app/bus/info/'+info.id+'"><img src="assets/images/btn_p3.gif" border="0"></a>';
    						marker.content	+= '</td>';
    					marker.content	+= '</tr>';
        			marker.content	+= '</table>';
        		marker.content	+= '</td>';
        	marker.content	+= '</tr>';
        	marker.content	+= '<tr height="20px">';
        		marker.content	+= '<td bgcolor="#DAE8EB" align="center">Car No</td>';
        		marker.content	+= '<td bgcolor="#FFFFFF" style="font-size:10pt;color:#0072BC;font-weight:bold;padding-left:4px">'+info.car_number+'</td>';
        	marker.content	+= '</tr>';
        	marker.content	+= '<tr height="20px">';
        		marker.content	+= '<td bgcolor="#DAE8EB" align="center">Bus ID</td>';
        		marker.content	+= '<td bgcolor=#FFFFFF style="font-size:10pt;padding-left:4px">'+info.code+'</td>';
        	marker.content	+= '</tr>';
        	marker.content	+= '<tr height="20px">';
        		marker.content	+= '<td bgcolor="#DAE8EB" align="center">Speed</td>';
        		marker.content	+= '<td bgcolor="#FFFFFF" style="font-size:10pt;'+info.speed_color+'font-weight:bold;padding-left:4px">'+info.speed+' km/h</td>';
        	marker.content	+= '</tr>';
        	marker.content	+= '<tr height="20px">';
        		marker.content	+= '<td bgcolor="#DAE8EB" align="center">Status</td>';
        		marker.content	+= '<td bgcolor="#FFFFFF" colspan="2" style="font-size:10pt;'+info.status_color+'font-weight:bold;padding-left:4px" colspan="2">'+info.operation_status+'</td>';
        	marker.content	+= '</tr>';
        marker.content	+='</table>';
        
        return marker.content;
	}
	var createBusMarker = function (info){
		// map icon
		info.icon = getBusIcon(info);
		
		// map marker
        var marker = new google.maps.Marker({
            map: $scope.map,
            position: new google.maps.LatLng(info.latitude, info.longitude),
            title: info.code,
            draggable: true,
            icon: info.icon            
        });
        
        marker.content	= buildBusContent(info);
        marker.oid = 'bus';
        marker.bid = info.id;
        marker.label = info.name;
        marker.connection_status_id = info.connection_status_id;
        marker.connection_status = info.connection_status;
        
        google.maps.event.addListener(
        	marker,
        	'click', (function(marker, $scope) {
        		return function() {
        			var compiled = $compile(marker.content)($scope);
        			//$scope.$apply();
        			infoWindow.setContent(compiled[0]);
        			infoWindow.open($scope.map, marker);
        			$scope.currentBusMark = marker;
        		};
        	})(marker, $scope)
        );
        google.maps.event.addListener(infoWindow,'closeclick',function(){
        	$scope.currentBusMark = null;
        });
        $rootScope.busMarkers.push(marker);
    }
	var createStationMarker = function (station){
		// map icon
		station.icon = getStationIcon(station);
		
		// map marker
        var marker = new google.maps.Marker({
            map: $scope.map,
            position: new google.maps.LatLng(station.latitude, station.longitude),
            title: station.code,
            draggable: true,
            icon: station.icon            
        });
        
        marker.content	= '<div><h4>'+station.code+'</h4>'+station.name+'</div>';
        marker.oid = 'station';
        marker.sid = station.id;
        marker.code = station.code;
        marker.name = station.name;
        
        google.maps.event.addListener(
        	marker,
        	'click', (function(marker, $scope) {
        		return function() {
        			var compiled = $compile(marker.content)($scope);
        			//$scope.$apply();
        			infoWindow.setContent(compiled[0]);
        			infoWindow.open($scope.map, marker);
        		};
        	})(marker, $scope)
        );
        $rootScope.stationMarkers.push(marker);
    }
	
	$scope.currentBusMark = null;
	var infoWindow = new google.maps.InfoWindow();
	var mapOptions = {
		zoom: 11,
		center: new google.maps.LatLng(-6.1753924,106.8271528),
		mapTypeId: google.maps.MapTypeId.ROADMAP
	}
	
	$scope.map = new google.maps.Map(document.getElementById('map'), mapOptions);
	
	$scope.showCurrentBus	= function(){
		$scope.busInfo($scope.currentBusMark.bid);
    	$scope.busDisplayed = true;
    	$scope.stationDisplayed = false;
	}
	$rootScope.openInfoWindow = function(e, selectedMarker){
        e.preventDefault();
        google.maps.event.trigger(selectedMarker, 'click');
        if(selectedMarker.oid == 'bus') {
        	$scope.busInfo(selectedMarker.bid);
        	$scope.busDisplayed = true;
        	$scope.stationDisplayed = false;
        }
        else if(selectedMarker.oid == 'station') {
        	$scope.stationInfo(selectedMarker.sid);
        	$scope.busDisplayed = false;
        	$scope.stationDisplayed = true;
        }
    }
	$http.get(BASE_URL+'/api/bus/lists').success(function(data){
		angular.forEach(data, function(value, key) {			
			createBusMarker(value);
		});
	});
	$http.get(BASE_URL+'/api/station/lists').success(function(data){
		angular.forEach(data, function(value, key) {			
			createStationMarker(value);
		});
	});
	$http.get(BASE_URL+'/api/line/lists').success(function(data){
		angular.forEach(data, function(value, key) {			
			var line = new google.maps.Polyline({
				path: angular.fromJson(value.lati_long),
	        	strokeColor: '#'+value.color,
	        	strokeOpacity: 1.0,
	        	strokeWeight: 3
	        });
	        line.setMap($scope.map);
		});
	});
	$scope.reloadBus	= function(){
		$.ajax({
            url: BASE_URL+'/api/bus/lists',
            dataType: 'json',
            headers: {'X-Authorization': $cookies.token},
            contentType: 'application/json; charset=utf-8',
            success: function (data) {
            	for(var i=0; i<data.length; i++){
            		data[i].baru = true;
            		var bus = data[i];
            		for(var j=0; j<$rootScope.busMarkers.length; j++) {
            			var node = $rootScope.busMarkers[j];
            			if(bus.id == node.bid) {
            				data[i].baru = false;
            				node.connection_status_id = bus.connection_status_id;
            		        node.connection_status = bus.connection_status;
            				node.setPosition(new google.maps.LatLng(bus.latitude, bus.longitude));
            				node.setIcon(getBusIcon(bus));
            				$rootScope.busMarkers[j] = node;
            				if($scope.currentBusMark != null) {
            					if(node.bid == $scope.currentBusMark.bid) {
                					infoWindow.setContent(buildBusContent(bus));
                					$scope.bus = bus;
                				}
            				}
            			}
            		}
            	}
            	// add new bus
            	for(var i=0; i<data.length; i++){
            		if(data[i].baru) createBusMarker(data[i]);
            	}
            	// remove lost bus
            	for(var i=0; i<$rootScope.busMarkers.length; i++) {
            		$rootScope.busMarkers[i].hapus = true;
            		for(var j=0; j<data.length; j++){
            			if($rootScope.busMarkers[i].bid == data[j].id) {
            				$rootScope.busMarkers[i].hapus = false;
            			}
            		}
            	}
            	for(var i=0; i<$rootScope.busMarkers.length; i++) {
            		if($rootScope.busMarkers[i].hapus) {
            			$rootScope.busMarkers[i].setMap(null);
            			$rootScope.busMarkers.splice(i,1);
            		}
            	}
            },
            error: function (response) {
				if(response.message == 'Unauthorized'){
					$rootScope.$broadcast('session-expired', {});
				}
            }
        });
	}
	$scope.reloadStation	= function(){
		$.ajax({
            url: BASE_URL+'/api/station/lists',
            dataType: 'json',
            headers: {'X-Authorization': $cookies.token},
            contentType: 'application/json; charset=utf-8',
            success: function (data) {
            	for(var i=0; i<data.length; i++){
            		data[i].baru = true;
            		var station = data[i];
            		for(var j=0; j<$rootScope.stationMarkers.length; j++) {
            			var node = $rootScope.stationMarkers[j];
            			if(station.id == node.sid) {
            				$scope.station = station;
            				data[i].baru = false;
            				node.setPosition(new google.maps.LatLng(station.latitude, station.longitude));
            				node.setIcon(getStationIcon(station));
            				$rootScope.stationMarkers[j] = node;
            			}
            		}
            	}
            	// add new station
            	for(var i=0; i<data.length; i++){
            		if(data[i].baru) createStationMarker(data[i]);
            	}
            	// remove lost station
            	for(var i=0; i<$rootScope.stationMarkers.length; i++) {
            		$rootScope.stationMarkers[i].hapus = true;
            		for(var j=0; j<data.length; j++){
            			if($rootScope.stationMarkers[i].sid == data[j].id) {
            				$rootScope.stationMarkers[i].hapus = false;
            			}
            		}
            	}
            	for(var i=0; i<$rootScope.stationMarkers.length; i++) {
            		if($rootScope.stationMarkers[i].hapus) {
            			$rootScope.stationMarkers[i].setMap(null);
            			$rootScope.stationMarkers.splice(i,1);
            		}
            	}
            },
            error: function (response) {
				if(response.message == 'Unauthorized'){
					$rootScope.$broadcast('session-expired', {});
				}
            }
        });
	}
	$rootScope.mapTimer = $interval(function () {
		$scope.reloadBus();
		$scope.reloadStation();
	}, 15 * 1000);
	
}])
;