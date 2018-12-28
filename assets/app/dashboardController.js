'use strict';

angular.module('app.controller', ['app.constant', 'ngCookies','angular-flot'])
.controller('DashboardController', ['$rootScope','$scope','$state','$http','$cookies','$interval', function ($rootScope,$scope,$state,$http,$cookies,$interval){
	if($cookies.token == null) $state.go('signin');
	$rootScope.$broadcast('tracking-disabled', {});
	//$rootScope.$broadcast('timer-disabled', {});
	var colors	= ['#4f5467','#f4b942','#01c0c8','#fb9678','#00c292','#4f5467','#f45641','#f4f141','#49f441','#418ef4','#7341f4','#e241f4','#f441c4','#f4418e'];
	$scope.busRegistered	= [];
	$scope.busStatus		= [];
	$scope.dataset 			= [];
	$http.get(BASE_URL+'/api/routeLine/bus').success(function(resp){
		$scope.busRegistered = resp;
		console.log(resp);
		for(var i=0; i<resp.length; i++) {
			$scope.dataset.push({label:resp[i].code+' ('+resp[i].total+')', data:Number(resp[i].total), color:colors[i]});
		}
	});
	
	$scope.options = {
		series: {
			pie: {
				show: true,
				label: {
					show: true
				}
			}
		},
		grid: {
			hoverable: true
		},
		legend: {
			show: false
		},
		tooltip: true,
		tooltipOpts: {
			content: "%p.0%, %s",
			shifts: {
				x: 20,
				y: 0
			},
			defaultTheme: false
		}
	};
	
	$scope.bsStick		= [];
	$scope.bsDataOn		= [];
	$scope.bsDataOff	= [];
	$http.get(BASE_URL+'/api/bus/status').success(function(resp){
		console.log(resp);
		for(var i=0; i<resp.length; i++){
			$scope.bsStick.push([i,resp[i].code]);
			$scope.bsDataOn.push([i, Number(resp[i].data[0].value)]);
			$scope.bsDataOff.push([i, Number(resp[i].data[1].value)]);
		}
		console.log('bsStick:');
		console.log($scope.bsStick);
		
		console.log('bsDataOn:');
		console.log($scope.bsDataOn);
		
		console.log('bsDataOff:');
		console.log($scope.bsDataOff);
		
	});
	$scope.bsDataset	= [{"label":"Online", "data":$scope.bsDataOn},{"label":"Offline", "data":$scope.bsDataOff}];
	$scope.bsOptions	= {
		bars: { show: true, barWidth: 0.6, series_spread: true, align: "center" },
		xaxis: { ticks: $scope.bsStick, autoscaleMargin: .10 },
		grid: { hoverable: true, clickable: true },
		tooltip: true,
		tooltipOpts: {
			content: function(label, x, y){
				return "%s %x : " + y;
			}
		}
	};
	
	$scope.rdlDataset	= [];
	$scope.rdlOptions	= {
		series: {
			pie: {
				show: true,
				innerRadius: 0.5,
				radius: 1,
				label: {
					show: true
				}
			}
		},
		grid: {
		        hoverable: true
		},
		tooltip: true,
		tooltipOpts: {
			content: "%p.0%, %s"
		}
	};
	$http.get(BASE_URL+'/api/routeDeviationLog/status').success(function(resp){
		$scope.rdlDataset	= resp;
	});
	
	$scope.odTick	= [];
	$scope.odData	= [];
	$scope.odDataset	= [];
	$scope.odOptions	= {
		series: {
			bars: {
				show: true
			}
		},
		bars: {
			align: "center",
			barWidth: 0.3
		},
		xaxis:{
			ticks: $scope.odTick,
			axisLabel: "Vehicle",
			axisLabelUseCanvas: true,
			axisLabelFontSizePixels: 12,
			axisLabelFontFamily: 'Verdana, Arial',
			axisLabelPadding: 10
		},
		yaxis: {
			axisLabel: "Distance",
			axisLabelUseCanvas: true,
			axisLabelFontSizePixels: 12,
			axisLabelFontFamily: 'Verdana, Arial',
			axisLabelPadding: 3,
			tickFormatter: function (v, axis) {
				return v + " Km";
			}
		},
		legend: {
			noColumns: 0,
			labelBoxBorderColor: "#000000",
			position: "ne"
		},
		grid: {
			hoverable: true,
			borderWidth: 2,
			backgroundColor: { colors: ["#ffffff", "#EDF5FF"] }
		},
		tooltip: true,
		tooltipOpts: {
			content: function(label, x, y){
				return "%s %x : " + y+" km";
			}
		}
	};
	$http.get(BASE_URL+'/api/operationDistance/today').success(function(resp){
		$scope.tickOD	= [];
		$scope.dataOD	= [];
		for(var i=0; i<resp.length; i++) {
			$scope.odTick.push([i, resp[i].name]);
			$scope.odData.push([i, Number(resp[i].total)]);
		}
		$scope.odDataset = [{label:'Operation Distance', data:$scope.odData, color:'#5482FF'}];
	});
	$scope.speedViolations	= [];
	$http.get(BASE_URL+'/api/speedViolationLog/list/_/_/1/10').success(function(resp){
		$scope.speedViolations	= resp;
	});
	$scope.routeDeviations	= [];
	$http.get(BASE_URL+'/api/routeDeviationLog/list/_/_/1/10').success(function(resp){
		$scope.routeDeviations	= resp;
	});
}])
;