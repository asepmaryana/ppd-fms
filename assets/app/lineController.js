angular.module('app.controller', ['ui.bootstrap'])
.controller('LineController', ['$rootScope','$scope','$http','$modal', function($rootScope,$scope,$http,$modal){
	$rootScope.$broadcast('tracking-disabled', {});
	$scope.crit			= {name:''};
	$scope.rows			= {};
	$scope.rows.pages   = [];
	$scope.page 		= 1;
	$scope.size  		= 10;
	$scope.unselected 	= [];
    $scope.selected 	= [];
    
	$scope.buildURL	  = function() {
		var name 		= $scope.crit.name;
        if(name == '') name = '_';
        if(name != '_') name = name.replace(/ /, '_');
		return BASE_URL+'/api/line/list/'+name;
	}
    $scope.setPage = function(page){
		if(!page || page < 1) page = 1;
		$scope.page  = page;
		$http.get($scope.buildURL()+'/'+$scope.page+'/'+$scope.size).success(function(response){
			$scope.rows 	= response;
			$scope.pages 	= _.range(1, ($scope.rows.totalPage+1));
			$scope.unselected = response.content;
		});
    }
    $scope.setPage();
    $scope.enterPressed = function (keyEvent) {
		if (keyEvent.keyCode == 13) $scope.setPage();
	};
	$scope.open = function (o, s) {
		var modalInstance = $modal.open({
			templateUrl: BASE_URL+'/assets/views/master/lineEdit.html',
            controller: 'LineEditController',
			size: s,
			resolve: {
				data: function() {
					return o;
				}
			}
		});
		modalInstance.result.then(function(selectedObject) {
			$scope.setPage($scope.page);
		});
	}
	$scope.remove = function(o) {
		bootbox.confirm("Apakah akan menghapus "+o.name+" ?", function(result) {
			if(result) {
				$http.get(BASE_URL+'/api/line/delete/'+o.id).success(function(response){
					$scope.rows.content = _.without($scope.rows.content, _.findWhere($scope.rows.content, {id:o.id}));
				});
			}
		});
	}
	$scope.trash	= function(){
		if($scope.selected.length == 0) swal('Exception', 'Belum ada data yang dipilih !');
		else {
			$http.post(BASE_URL+'/api/line/trash', $scope.selected).success(function(response){
				$scope.setPage();
			});
		}
	}
	$scope.selectAll = function($event){
        if($event.target.checked){
            for (var i = 0; i < $scope.unselected.length; i++) {
                var p = $scope.unselected[i];
                if($scope.selected.indexOf(p.id) < 0){
                    $scope.selected.push(p.id);
                }
            }
        } else {
            $scope.selected = [];
        }
        $scope.printSelected();
    }
	$scope.updateSelected = function($event, r){
        var checkbox = $event.target;
        if(checkbox.checked  && $scope.selected.indexOf(r.id) < 0){
            $scope.selected.push(r.id);
        } else {
            $scope.selected.splice($scope.selected.indexOf(r.id), 1);
        }
        $scope.printSelected();
    }
    $scope.isSelected = function(r){
        return $scope.selected.indexOf(r.id) >= 0;
    }
    $scope.isAllSelected = function(){
        return $scope.unselected.length === $scope.selected.length && $scope.unselected.length > 0;
    }
    $scope.printSelected  = function(){
		console.log('selected: ');
		console.log($scope.selected);
	}
}])
.controller('LineEditController', ['$scope','$compile','$http','$modalInstance','$timeout','data', function($scope,$compile,$http,$modalInstance,$timeout,data){
	var original= data;
	var id		= 0;
	var map;
	var line;
	var path	= [];
	var markers	= [];
	var color	= 'e80030';
	
	$scope.data    		= angular.copy(data);
	$scope.setColor		= function(val){
		color = val;
		line.setOptions({strokeColor: '#'+val});
	}
	$scope.reset	= function() {
		path	= [];
		markers	= [];
	}
	$scope.cancel 	= function() {
		$modalInstance.dismiss('Close');
	};
	$scope.title = (angular.isDefined(data)) ? 'Edit Line' : 'New Line';
	$scope.buttonText = (angular.isDefined(data)) ? 'Update' : 'Save';
	$scope.isClean = function() {
		return angular.equals(original, $scope.data);
	}
	$scope.save = function (o) {
		o.lati_long	= [];
		for(var i=0; i<markers.length; i++){
			var latLng = markers[i];
			o.lati_long.push({lat:latLng.getPosition().lat(), lng:latLng.getPosition().lng()});
		}
		if(angular.isDefined(o.id)){
			$http.post(BASE_URL+'/api/line/update/'+o.id, o).then(function (result) {
				var x = angular.copy(o);
				x.save = 'update';
				$modalInstance.close(x);
			});
		} else{
			$http.post(BASE_URL+'/api/line/save', o).then(function (result) {
				var x = angular.copy(o);
				x.save = 'insert';
				x.id = result.id;                    
				$modalInstance.close(x);
			});
		}
	};
	
	var infoWindow = new google.maps.InfoWindow();
	var createMarker = function (latLng){
		id++;
		var data	= {id:id,title:'Point '+id};
		path 		= line.getPath();
		path.push(latLng);		
        var marker = new google.maps.Marker({
            map: map,
            position: latLng,
            title: ''+path.getLength()+'',
            draggable: true,
            icon: BASE_URL+'/assets/images/'+color+'.png'            
        });
        marker.id		= data.id;
        marker.line		= path.length;
        marker.content	= '<div><h5>'+data.title+'</h5></div>';
        google.maps.event.addListener(
        	marker,
        	'click', (function(marker, $scope) {
        		return function() {
        			var compiled = $compile(marker.content)($scope);
        			//$scope.$apply();
        			infoWindow.setContent(compiled[0]);
        			infoWindow.open(map, marker);
        		};
        	})(marker, $scope)
        );
        
        markers.push(marker);
    }
	$modalInstance.opened.then(function() {
	    function initialize() {
	        var latlng = new google.maps.LatLng(-6.1753924,106.8271528);
	        var myOptions = {
	            zoom: 11,
	            center: latlng,
	            mapTypeId: google.maps.MapTypeId.ROADMAP
	        };
	        map = new google.maps.Map(document.getElementById("map"), myOptions);
	        line = new google.maps.Polyline({
	        	strokeColor: '#'+color,
	        	strokeOpacity: 1.0,
	        	strokeWeight: 3
	        });
	        line.setMap(map);
	        
	        if((angular.isDefined(data))) {
	        	console.log('debug data:');
	    		color = data.color;
	    		var lat_lon = angular.fromJson(data.lati_long);
	    		for(var i=0; i<lat_lon.length; i++){
	    			var point = lat_lon[i];
	    			console.log(point);
	    			createMarker(new google.maps.LatLng(point.lat, point.lng));
	    		}
	    	}
	    	else {
	    		$scope.data	= {color:'e80030'};
	    	}
	        
	        google.maps.event.addListener(map, "rightclick", function(event){ 
	    		var lat = event.latLng.lat();
	            var lng = event.latLng.lng();
	            //console.log("Lat=" + lat + "; Lng=" + lng);
	            createMarker(event.latLng);
	    	});
	    }
	    $timeout(function() { initialize() }, 1000);
	});
}])
;
