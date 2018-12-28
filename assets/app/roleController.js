angular.module('app.controller', ['ui.bootstrap'])
.controller('RoleController', ['$rootScope','$scope','$http','$modal', function($rootScope,$scope,$http,$modal){
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
		return BASE_URL+'/api/role/list/'+name;
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
			templateUrl: BASE_URL+'/assets/views/master/roleEdit.html',
            controller: 'RoleEditController',
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
				$http.get(BASE_URL+'/api/role/delete/'+o.id).success(function(response){
					$scope.rows.content = _.without($scope.rows.content, _.findWhere($scope.rows.content, {id:o.id}));
				});
			}
		});
	}
	$scope.trash	= function(){
		if($scope.selected.length == 0) swal('Exception', 'Belum ada data yang dipilih !');
		else {
			$http.post(BASE_URL+'/api/role/trash', $scope.selected).success(function(response){
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
.controller('RoleEditController', ['$scope', '$http', '$modalInstance', 'data', function($scope, $http, $modalInstance, data){
	var original 		= data;
	$scope.data    		= angular.copy(data);
	$scope.cancel 	= function () {
		$modalInstance.dismiss('Close');
	};
	$scope.title = (angular.isDefined(data)) ? 'Edit' : 'New';
	$scope.buttonText = (angular.isDefined(data)) ? 'Update' : 'Save';
	$scope.isClean = function() {
		return angular.equals(original, $scope.data);
	}
	$scope.save = function (o) {
		if(angular.isDefined(o.id)){
			$http.post(BASE_URL+'/api/role/update/'+o.id, o).then(function (result) {
				var x = angular.copy(o);
				x.save = 'update';
				$modalInstance.close(x);
			});
		} else{
			$http.post(BASE_URL+'/api/role/save', o).then(function (result) {
				var x = angular.copy(o);
				x.save = 'insert';
				x.id = result.id;                    
				$modalInstance.close(x);
			});
		}
	};
}])
;
