'use strict';
angular.module('app.controller', ['app.constant', 'ngCookies'])
.controller('BusInfoController', ['$rootScope','$scope','$stateParams','$state','$http','$cookies', function ($rootScope,$scope,$stateParams,$state,$http,$cookies){
	if($cookies.token == null) $state.go('signin');
	$rootScope.$broadcast('tracking-disabled', {});
	
	var bid	= $stateParams.bid;
	
	$scope.routeLines		= [];
	$scope.manCompanies		= [];
	$scope.serviceGroups	= [];
	$scope.busDevices		= [];
	$scope.transCompanies	= [];
	
	$http.get(BASE_URL+'/api/routeLine/lists').success(function(data){
		$scope.routeLines		= data;
	});
	$http.get(BASE_URL+'/api/manCompany/lists').success(function(data){
		$scope.manCompanies		= data;
	});
	$http.get(BASE_URL+'/api/serviceGroup/lists').success(function(data){
		$scope.serviceGroups	= data;
	});
	$http.get(BASE_URL+'/api/busDevice/lists').success(function(data){
		$scope.busDevices	= data;
	});
	$http.get(BASE_URL+'/api/transCompany/lists').success(function(data){
		$scope.transCompanies	= data;
	});
	
	$scope.data		= null;
	var original 	= null;
	$http.get(BASE_URL+'/api/bus/info/'+bid).success(function(data){
		$scope.data	= data;
		original	= angular.copy(data);
	});
	$scope.isClean = function() {
		return angular.equals(original, $scope.data);
	}
	$scope.save = function (o) {
		swal({
			title: "Konfirmasi",
			text: "Apakah anda mau melakukan perubahan ?",
			icon: "warning",
			buttons: true,
			dangerMode: true,
			showCancelButton: true,
		    confirmButtonColor: '#DD6B55',
		    confirmButtonText: 'Ya',
		    cancelButtonText: 'Tidak',
		    closeOnConfirm: true,
		    closeOnCancel: true
		},
		function(isConfirm){
			if (isConfirm) {
				$http.post(BASE_URL+'/api/bus/update/'+o.id, o)
				.success(function (res) {
					swal('Success', 'Update berhasil.');
				})
				.error(function (res) {
					swal('Success', 'Update gagal !');
				});
			}
		});
	};
	$scope.reset = function(){
		$scope.data = original;
	}
}])
;