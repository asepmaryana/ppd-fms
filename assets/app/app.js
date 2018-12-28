'use strict';

angular.module('app', ['ui.router', 'oc.lazyLoad', 'ngAnimate', 'ngCookies', 'ui.bootstrap', 'app.controller', 'app.directive', 'angularMoment','angular-flot'])
.run(function($rootScope, $state, $interval, $timeout, $cookies, $http, amMoment) {	
	amMoment.changeLocale('id');
	$rootScope.$on('loading:show', function() { $(".preloader").show(); });
	$rootScope.$on('loading:hide', function() { $(".preloader").hide(); });
	$rootScope.$on('$locationChangeStart', function(event, next, prev) {});
	$rootScope.$on('tracking-enabled', function(event, args) {
		$rootScope.showTracking	= true;
	});
	$rootScope.$on('tracking-disabled', function(event, args) {
		$rootScope.showTracking	= false;
		$rootScope.$broadcast('timer-disabled', {});
	});
	$rootScope.$on('login-succeed', function(event, args) {
		$rootScope.authenticated	= true;
		$http.get(BASE_URL+'/api/auth/info').success(function(data){
			$rootScope.user = data;
		});
	});
	$rootScope.$on('session-expired', function(event, args) {
		$rootScope.$broadcast('timer-disabled', {});
		$rootScope.authenticated	= false;
		delete $rootScope.user;
		delete $cookies.token;
		delete $http.defaults.headers.common['X-Authorization'];
		$timeout( function(){ window.location.href = BASE_URL; }, 1000);
	});
	$rootScope.$on('auth-not-authenticated', function(event, args) {
		$rootScope.authenticated	= false;
		delete $rootScope.user;
		delete $cookies.token;
		$state.go('signin');
	});
	$rootScope.$on('timer-disabled', function(event, args) {
		if (angular.isDefined($rootScope.mapTimer)) $interval.cancel($rootScope.mapTimer);
	});
})
.filter('tanggal', function () { 
    return function (text) {    	
        return (text == '0000-00-00') ? '' : moment(text).format('DD-MM-YYYY');
    };    
})
.config(function($stateProvider,$urlRouterProvider,$httpProvider) {
    $urlRouterProvider.otherwise('/welcome/signin');
    $stateProvider
	    .state('welcome', {
	    	abstract: true,
	    	url: '/welcome',
	    	templateUrl: 'assets/views/welcome.html',
	    	controller: 'WelcomeController'
	    })
	    .state('signin', {
	    	url: '/signin',
			parent: 'welcome',
	    	templateUrl: 'assets/views/signin.html',
	    	controller: 'SignInController'
	    })
	    .state('signup', {
	    	url: '/signup',
			parent: 'welcome',
	    	templateUrl: 'assets/views/signup.html',
	    	controller: 'SignUpController'
	    })
		.state('reset', {
	    	url: '/reset',
			parent: 'welcome',
	    	templateUrl: 'assets/views/reset.html',
	    	controller: 'ResetController'
	    })
	    .state('app', {
	    	abstract: true,
	    	url: '/app',
	    	templateUrl: 'assets/views/base.html',
	    	controller: 'BaseController'
	    })
	    .state('app.dashboard', {
	    	url: '/dashboard',
	    	parent: 'app',
	    	views:{
				'page':{
					templateUrl: 'assets/views/dashboard.html',
					controller: 'DashboardController'
				}
			},
			resolve: {
        		loadModule: ['$ocLazyLoad', function($ocLazyLoad){
					return $ocLazyLoad.load(['assets/app/dashboardController.js']);
				}]
        	}
	    })
	    .state('app.tracking', {
	    	url: '/tracking',
	    	parent: 'app',
	    	views:{
				'page':{
					templateUrl: 'assets/views/tracking.html',
					controller: 'TrackingController'
				}
			},
			resolve: {
        		loadModule: ['$ocLazyLoad', function($ocLazyLoad){
					return $ocLazyLoad.load(['assets/app/trackingController.js']);
				}]
        	}
	    })
	    .state('app.station', {
	    	url: '/station',
	    	parent: 'app',
	    	views:{
				'page':{
					templateUrl: 'assets/views/master/station.html',
					controller: 'StationController'
				}
			},
			resolve: {
        		loadModule: ['$ocLazyLoad', function($ocLazyLoad){
					return $ocLazyLoad.load(['assets/app/stationController.js']);
				}]
        	}
	    })
	    .state('app.line', {
	    	url: '/line',
	    	parent: 'app',
	    	views:{
				'page':{
					templateUrl: 'assets/views/master/line.html',
					controller: 'LineController'
				}
			},
			resolve: {
        		loadModule: ['$ocLazyLoad', function($ocLazyLoad){
					return $ocLazyLoad.load(['assets/app/lineController.js']);
				}]
        	}
	    })
	    .state('app.bus', {
	    	url: '/bus',
	    	parent: 'app',
	    	views:{
				'page':{
					templateUrl: 'assets/views/master/bus.html',
					controller: 'BusController'
				}
			},
			resolve: {
        		loadModule: ['$ocLazyLoad', function($ocLazyLoad){
					return $ocLazyLoad.load(['assets/app/busController.js']);
				}]
        	}
	    })
	    .state('app.busDriving', {
	    	url: '/bus/driving/:bid',
	    	parent: 'app',
	    	views:{
				'page':{
					templateUrl: 'assets/views/busDriving.html',
					controller: 'BusDrivingController'
				}
			},
			resolve: {
        		loadModule: ['$ocLazyLoad', function($ocLazyLoad){
					return $ocLazyLoad.load(['assets/app/busDrivingController.js']);
				}]
        	}
	    })
	    .state('app.busInfo', {
	    	url: '/bus/info/:bid',
	    	parent: 'app',
	    	views:{
				'page':{
					templateUrl: 'assets/views/busInfo.html',
					controller: 'BusInfoController'
				}
			},
			resolve: {
        		loadModule: ['$ocLazyLoad', function($ocLazyLoad){
					return $ocLazyLoad.load(['assets/app/busInfoController.js']);
				}]
        	}
	    })
	    .state('app.routeMap', {
	    	url: '/routeMap',
	    	parent: 'app',
	    	views:{
				'page':{
					templateUrl: 'assets/views/master/routeMap.html',
					controller: 'RouteMapController'
				}
			},
			resolve: {
        		loadModule: ['$ocLazyLoad', function($ocLazyLoad){
					return $ocLazyLoad.load(['assets/app/routeMapController.js']);
				}]
        	}
	    })
	    .state('app.garage', {
	    	url: '/garage',
	    	parent: 'app',
	    	views:{
				'page':{
					templateUrl: 'assets/views/master/garage.html',
					controller: 'GarageController'
				}
			},
			resolve: {
        		loadModule: ['$ocLazyLoad', function($ocLazyLoad){
					return $ocLazyLoad.load(['assets/app/garageController.js']);
				}]
        	}
	    })
	    .state('app.driver', {
	    	url: '/driver',
	    	parent: 'app',
	    	views:{
				'page':{
					templateUrl: 'assets/views/master/driver.html',
					controller: 'DriverController'
				}
			},
			resolve: {
        		loadModule: ['$ocLazyLoad', function($ocLazyLoad){
					return $ocLazyLoad.load(['assets/app/driverController.js']);
				}]
        	}
	    })
	    .state('app.serviceGroup', {
	    	url: '/serviceGroup',
	    	parent: 'app',
	    	views:{
				'page':{
					templateUrl: 'assets/views/master/serviceGroup.html',
					controller: 'ServiceGroupController'
				}
			},
			resolve: {
        		loadModule: ['$ocLazyLoad', function($ocLazyLoad){
					return $ocLazyLoad.load(['assets/app/serviceGroupController.js']);
				}]
        	}
	    })
	    .state('app.transCompany', {
	    	url: '/transCompany',
	    	parent: 'app',
	    	views:{
				'page':{
					templateUrl: 'assets/views/master/transCompany.html',
					controller: 'TransCompanyController'
				}
			},
			resolve: {
        		loadModule: ['$ocLazyLoad', function($ocLazyLoad){
					return $ocLazyLoad.load(['assets/app/transCompanyController.js']);
				}]
        	}
	    })
	    .state('app.manCompany', {
	    	url: '/manCompany',
	    	parent: 'app',
	    	views:{
				'page':{
					templateUrl: 'assets/views/master/manCompany.html',
					controller: 'ManCompanyController'
				}
			},
			resolve: {
        		loadModule: ['$ocLazyLoad', function($ocLazyLoad){
					return $ocLazyLoad.load(['assets/app/manCompanyController.js']);
				}]
        	}
	    })
	    .state('app.busDevice', {
	    	url: '/busDevice',
	    	parent: 'app',
	    	views:{
				'page':{
					templateUrl: 'assets/views/master/busDevice.html',
					controller: 'BusDeviceController'
				}
			},
			resolve: {
        		loadModule: ['$ocLazyLoad', function($ocLazyLoad){
					return $ocLazyLoad.load(['assets/app/busDeviceController.js']);
				}]
        	}
	    })
	    .state('app.routeLine', {
	    	url: '/routeLine',
	    	parent: 'app',
	    	views:{
				'page':{
					templateUrl: 'assets/views/master/routeLine.html',
					controller: 'RouteLineController'
				}
			},
			resolve: {
        		loadModule: ['$ocLazyLoad', function($ocLazyLoad){
					return $ocLazyLoad.load(['assets/app/routeLineController.js']);
				}]
        	}
	    })
	    .state('app.role', {
	    	url: '/role',
	    	parent: 'app',
	    	views:{
				'page':{
					templateUrl: 'assets/views/master/role.html',
					controller: 'RoleController'
				}
			},
			resolve: {
        		loadModule: ['$ocLazyLoad', function($ocLazyLoad){
					return $ocLazyLoad.load(['assets/app/roleController.js']);
				}]
        	}
	    })
	    .state('app.user', {
	    	url: '/user',
	    	parent: 'app',
	    	views:{
				'page':{
					templateUrl: 'assets/views/master/user.html',
					controller: 'UsersController'
				}
			},
			resolve: {
        		loadModule: ['$ocLazyLoad', function($ocLazyLoad){
					return $ocLazyLoad.load(['assets/app/usersController.js']);
				}]
        	}
	    })
	    .state('app.busConnStatus', {
	    	url: '/busConnStatus',
	    	parent: 'app',
	    	views:{
				'page':{
					templateUrl: 'assets/views/busConnStatus.html',
					controller: 'BusConnStatusController'
				}
			},
			resolve: {
        		loadModule: ['$ocLazyLoad', function($ocLazyLoad){
					return $ocLazyLoad.load(['assets/app/busConnStatusController.js']);
				}]
        	}
	    })
	    .state('app.busDrivingRecord', {
	    	url: '/busDrivingRecord',
	    	parent: 'app',
	    	views:{
				'page':{
					templateUrl: 'assets/views/busDrivingRecord.html',
					controller: 'BusDrivingRecordController'
				}
			},
			resolve: {
        		loadModule: ['$ocLazyLoad', function($ocLazyLoad){
					return $ocLazyLoad.load(['assets/app/busDrivingRecordController.js']);
				}]
        	}
	    })
	    .state('app.busSpeedRecord', {
	    	url: '/busSpeedRecord',
	    	parent: 'app',
	    	views:{
				'page':{
					templateUrl: 'assets/views/busSpeedRecord.html',
					controller: 'BusSpeedRecordController'
				}
			},
			resolve: {
        		loadModule: ['$ocLazyLoad', function($ocLazyLoad){
					return $ocLazyLoad.load(['assets/app/busSpeedRecordController.js']);
				}]
        	}
	    })
	    .state('app.busViolationRecord', {
	    	url: '/busViolationRecord',
	    	parent: 'app',
	    	views:{
				'page':{
					templateUrl: 'assets/views/busViolationRecord.html',
					controller: 'BusViolationRecordController'
				}
			},
			resolve: {
        		loadModule: ['$ocLazyLoad', function($ocLazyLoad){
					return $ocLazyLoad.load(['assets/app/busViolationRecordController.js']);
				}]
        	}
	    })
	    .state('app.stationPassedRecord', {
	    	url: '/stationPassedRecord',
	    	parent: 'app',
	    	views:{
				'page':{
					templateUrl: 'assets/views/stationPassedRecord.html',
					controller: 'StationPassedRecordController'
				}
			},
			resolve: {
        		loadModule: ['$ocLazyLoad', function($ocLazyLoad){
					return $ocLazyLoad.load(['assets/app/stationPassedRecordController.js']);
				}]
        	}
	    })
	    .state('app.busPassedRecord', {
	    	url: '/busPassedRecord',
	    	parent: 'app',
	    	views:{
				'page':{
					templateUrl: 'assets/views/busPassedRecord.html',
					controller: 'BusPassedRecordController'
				}
			},
			resolve: {
        		loadModule: ['$ocLazyLoad', function($ocLazyLoad){
					return $ocLazyLoad.load(['assets/app/busPassedRecordController.js']);
				}]
        	}
	    })
        ;
    
    $httpProvider.interceptors.push(['$rootScope', '$q', '$cookies', 'EVENTS', function ($rootScope, $q, $cookies, EVENTS) {
		return {
			'request': function (config) {
				$rootScope.$broadcast('loading:show');
				config.headers = config.headers || {};
				return config;
			},
			'response': function(response) {
				$rootScope.$broadcast('loading:hide');
				return response;
			},
			'responseError': function (response) {
				$rootScope.$broadcast('loading:hide');
				$rootScope.$broadcast({
					401: EVENTS.notAuthenticated,
					403: EVENTS.notAuthorized,
					500: EVENTS.internalError
				}[response.status], response);
								
				return $q.reject(response);
			}
		};
    }]);
    
  })
  ;