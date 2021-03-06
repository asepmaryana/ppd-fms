angular.module('app.controller', ['ui.bootstrap'])
.controller('RouteMapController', ['$rootScope','$scope','$http','$modal', function($rootScope,$scope,$http,$modal){
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
		return BASE_URL+'/api/routeMap/list/'+name;
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
			templateUrl: BASE_URL+'/assets/views/master/routeMapEdit.html',
            controller: 'RouteMapEditController',
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
				$http.get(BASE_URL+'/api/routeMap/delete/'+o.id).success(function(response){
					$scope.rows.content = _.without($scope.rows.content, _.findWhere($scope.rows.content, {id:o.id}));
				});
			}
		});
	}
	$scope.trash	= function(){
		if($scope.selected.length == 0) swal('Exception', 'Belum ada data yang dipilih !');
		else {
			$http.post(BASE_URL+'/api/routeMap/trash', $scope.selected).success(function(response){
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
.controller('RouteMapEditController', ['$scope','$compile','$http','$modalInstance','$timeout','data', function($scope,$compile,$http,$modalInstance,$timeout,data){
	var original	= data;
	$scope.data    	= angular.copy(data);
	$scope.lines	= [];
	$http.get(BASE_URL+'/api/line/lists').success(function(data){
		$scope.lines	= data;
	});
	$http.get(BASE_URL+'/api/serviceGroup/lists').success(function(data){
		$scope.serviceGroups	= data;
	});
	if((angular.isDefined(data))) {
		$scope.data.lines	= [];
		$http.get(BASE_URL+'/api/routeMap/line/'+data.id).success(function(data){
			for(var i=0; i<data.length; i++) $scope.data.lines.push(data[i].line_id);
		});
	}
	else {
		$scope.data	= {lines:[]};
	}
	$scope.cancel 	= function() {
		$modalInstance.dismiss('Close');
	};
	$scope.title = (angular.isDefined(data)) ? 'Edit Route Map' : 'New Route Map';
	$scope.buttonText = (angular.isDefined(data)) ? 'Update' : 'Save';
	$scope.isClean = function() {
		return angular.equals(original, $scope.data);
	}
	$scope.save = function (o) {
		if(angular.isDefined(o.id)){
			$http.post(BASE_URL+'/api/routeMap/update/'+o.id, o).then(function (result) {
				var x = angular.copy(o);
				x.save = 'update';
				$modalInstance.close(x);
			});
		} else{
			$http.post(BASE_URL+'/api/routeMap/save', o).then(function (result) {
				var x = angular.copy(o);
				x.save = 'insert';
				x.id = result.id;                    
				$modalInstance.close(x);
			});
		}
	};
	$scope.isSelected = function(r){
        return $scope.data.lines.indexOf(r.id) >= 0;
    }
	$scope.updateSelected = function($event, r){
        var checkbox = $event.target;
        if(checkbox.checked  && $scope.data.lines.indexOf(r.id) < 0){
            $scope.data.lines.push(r.id);
        } else {
            $scope.data.lines.splice($scope.data.lines.indexOf(r.id), 1);
        }
        $scope.printSelected();
    }
	$scope.printSelected  = function(){
		console.log('selected: ');
		console.log($scope.data.lines);
	}
}])
;
