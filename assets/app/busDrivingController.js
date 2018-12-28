'use strict';
angular.module('app.controller', ['app.constant', 'ngCookies'])
.controller('BusDrivingController', ['$rootScope','$scope','$stateParams','$state','$http','$cookies', function ($rootScope,$scope,$stateParams,$state,$http,$cookies){
	if($cookies.token == null) $state.go('signin');
	$rootScope.$broadcast('tracking-disabled', {});
	
	var bid	= $stateParams.bid;
	$scope.crit	= {bid:bid,sdate:moment().format('YYYY-MM-DD'),edate:moment().format('YYYY-MM-DD')};
	$scope.bus	= null;
	$http.get(BASE_URL+'/api/bus/info/'+bid).success(function(data){
		$scope.bus	= data;
	});
	$scope.buildURL	  = function() {
		var sdate	= $scope.crit.sdate;
		var edate 	= $scope.crit.edate;
		if(sdate == '') sdate = moment().format('YYYY-MM-DD');
		if(edate == '') edate = moment().format('YYYY-MM-DD');
		return BASE_URL+'/api/busDrivingLog/list/'+bid+'/'+sdate+'/'+edate;
	}
	$scope.page = 1;
	$scope.size = 10;
	$scope.setPage = function(page, doc){
		if(doc != '') window.open($scope.buildURL()+'/_/_/'+doc);
		else {
			if(!page || page < 1) page = 1;
			$scope.page  = page;
			$http.get($scope.buildURL()+'/'+$scope.page+'/'+$scope.size).success(function(response){
				$scope.rows 	= response;
				$scope.pages 	= _.range(1, ($scope.rows.totalPage+1));
			});
		}
		
    }
    //$scope.setPage();
}])
;