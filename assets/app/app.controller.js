'use strict';

angular.module('app.controller', ['app.constant', 'ngCookies'])
.controller('WelcomeController', ['$scope', function ($scope){
	
}])
.controller('SignInController', ['$rootScope','$scope','$state','$http','$cookies','$location', function ($rootScope,$scope,$state,$http,$cookies,$location){
	if($cookies.token != null) $state.go('app.dashboard');
	$scope.data	= {username:'', password:'', remember:''};
	$scope.enterPressed = function (keyEvent) {
		if (keyEvent.keyCode == 13) $scope.login($scope.data);
	};
	$scope.login	= function(data) {
		if(data.username == '') swal('Exception', 'Username belum diisi !');
		else if(data.password == '') swal('Exception', 'Password belum diisi !');
		else 
		{
			$http.post(BASE_URL + '/api/auth/login', data)
			.success(function(res){
				$cookies.token = res.token;
				$rootScope.authority_id = res.authority_id;
				$http.defaults.headers.common['X-Authorization'] = res.token;
				$state.go('app.dashboard');
			})
			.error(function(res){
				swal('Exception', res.message);
			});
		}
	};
}])
.controller('SignUpController', ['$rootScope','$scope','$state','$http','$cookies','$location', function ($rootScope,$scope,$state,$http,$cookies,$location){
	if($cookies.token != null) $state.go('app.dashboard');
	$scope.enterPressed = function (keyEvent) {
		if (keyEvent.keyCode == 13) $scope.signup($scope.data);
	};
	$scope.signup	= function(data) {
		if(data.password != data.confirm) swal('Exception', 'Password yang dimasukan tidak sama !');
		else 
		{
			$http.post(BASE_URL + '/api/auth/signup', data)
			.success(function(res){
				swal('Exception', res.message);
				$state.go('signin');
			})
			.error(function(res){
				swal('Exception', res.message);
			});
		}
	};
}])
.controller('ResetController', ['$rootScope','$scope','$state','$http','$cookies','$location', function ($rootScope,$scope,$state,$http,$cookies,$location){
	if($cookies.token != null) $state.go('app.dashboard');
	$scope.data	= {username:'', password:'', confirm:''};
	$scope.enterPressed = function (keyEvent) {
		if (keyEvent.keyCode == 13) $scope.signup($scope.data);
	};
	$scope.reset	= function(data) {
		
	};
}])
.controller('BaseController', ['$rootScope','$scope','$http','$timeout','$cookies','EVENTS', function ($rootScope,$scope,$http,$timeout,$cookies,EVENTS) {
	$rootScope.authenticated	= false;
	$rootScope.showTracking	= false;
	$rootScope.user = {};
	$http.get(BASE_URL+'/api/auth/info').success(function(data){
		$rootScope.user = data;
	});
	$rootScope.isAuthenticated	= function(){
		return $rootScope.authenticated;
	}
	$scope.logout = function () {
		swal({
			title: "Konfirmasi",
			text: "Apakah anda mau keluar ?",
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
				$http.get(BASE_URL + '/api/auth/logout')
				.success(function (res) {
					swal('Success', 'Anda telah berhasil logout.');
					$rootScope.$broadcast('session-expired', {});
				})
				.error(function (res) {
					$rootScope.$broadcast('session-expired', {});
				});
			}
		});
	};
	$scope.item	= null;
	$rootScope.bus	= [];
	$rootScope.busMarkers 		= [];
	$rootScope.stationMarkers 	= [];
	$rootScope.select		= function(item){
		$scope.item = item;
		console.log('call from jquery:');
		console.log(item);
		var e = jQuery.Event("click");
		for(var i=0; i<$rootScope.busMarkers.length; i++) {
			if(item.id == $rootScope.busMarkers[i].bid) $rootScope.openInfoWindow(e, $rootScope.busMarkers[i]);
		}
	}
	$scope.go	= function(){
		var e = jQuery.Event("click");
		for(var i=0; i<$rootScope.busMarkers.length; i++) {
			if($scope.item.id == $rootScope.busMarkers[i].bid) $rootScope.openInfoWindow(e, $rootScope.busMarkers[i]);
		}
	}
	$scope.$on(EVENTS.notAuthorized, function(event) {
		//swal('Exception', 'Anda tidak diijinkan untuk membuka resource tersebut.');
		$rootScope.$broadcast('session-expired', {});
	});
	$scope.$on(EVENTS.notAuthenticated, function(event) {
		swal('Exception', 'Sesi anda telah berakhir, silahkan login kembali.');
		$rootScope.$broadcast('session-expired', {});
	});
	$scope.$on(EVENTS.internalError, function(event) {
		swal('Exception', 'Error di sisi server.');
	});
	$scope.$on(EVENTS.profileChanged, function(event, args) {
		$rootScope.user = args.user;
	});
	$scope.redirect	= function(milis) {
		$timeout( function(){ window.location.href = BASE_URL; }, milis);
	}	
}])
;