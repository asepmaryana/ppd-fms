angular.module('app.controller', ['ui.bootstrap'])
.controller('BusDrivingRecordController', ['$rootScope','$scope','$http','$location', function($rootScope,$scope,$http,$location){
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
		return BASE_URL+'/api/bus/list/'+name;
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
}])
;
