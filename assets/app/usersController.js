angular.module('app.controller', ['ui.bootstrap'])
.controller('UsersController', ['$rootScope','$scope','$http','$modal', function($rootScope,$scope,$http,$modal){
	$scope.crit			= {name:''};
	$scope.rows			= {};
	$scope.rows.pages   = [];
	$scope.page 		= 1;
	$scope.size  		= 10;
	
	$scope.buildURL	  = function() {
		var nama 		= $scope.crit.name;
        if(nama == '') nama = '_';
        if(nama != '_') nama = nama.replace(/ /, '_');
		
		return BASE_URL+'/api/user/list/'+nama;
	}
    $scope.setPage = function(page){
		if(!page || page < 1) page = 1;
		$scope.page  = page;
		$http.get($scope.buildURL()+'/'+$scope.page+'/'+$scope.size).success(function(response){
			$scope.rows 	= response;
			$scope.pages 	= _.range(1, ($scope.rows.totalPage+1));
		});
    }
    $scope.setPage();
	$scope.open = function (o, s) {
		var modalInstance = $modal.open({
			templateUrl: BASE_URL+'/assets/views/master/userEdit.html',
            controller: 'UsersEditController',
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
		bootbox.confirm("Apakah anda akan menghapus "+o.nama+" ?", function(result) {
			if(result) {
				$http.get(BASE_URL+'/api/user/remove/'+o.id).success(function(response){
					$scope.rows.content = _.without($scope.rows.content, _.findWhere($scope.rows.content, {id:o.id}));
				});
			}
		});
	}
}])
.controller('UsersEditController', ['$scope','$http','$modalInstance','data', function($scope,$http,$modalInstance,data){
	var original 		= data;
	$scope.data    		= angular.copy(data);
	$scope.roles		= [];
	
	$http.get(BASE_URL+'/api/role/lists').success(function(data){
		$scope.roles 	= data;
	});
	$scope.cancel 	= function () {
		$modalInstance.dismiss('Close');
	};
	$scope.title = (angular.isDefined(data)) ? 'Edit' : 'New';
	$scope.buttonText = (angular.isDefined(data)) ? 'Update' : 'Save';
	$scope.isClean = function() {
		return angular.equals(original, $scope.data);
	}
	$scope.files = [];
	$scope.save = function (o) {		
		o.opassword		= (angular.isDefined(original)) ? original.password : o.password;
		$scope.data.foto = $scope.files[0];
		if(angular.isDefined(o.id)){
			$http({
				method  : 'POST',
				url     : BASE_URL+'/api/user/update/'+o.id,
				processData: false,
				transformRequest: function (data) {
					var formData = new FormData();
					formData.append("foto", $scope.data.foto);
					formData.append("data", angular.toJson($scope.data));
					return formData;  
				},
				data : $scope.data,
				headers: { 'Content-Type': undefined }
			})
			.success(function(res){
				var x = angular.copy(o);
				x.save = 'update';
				$modalInstance.close(x);
			})
			.error(function(res) {
	    		swal('Exception', res.message);
	    	});
		} else{
			$http({
				method  : 'POST',
				url     : BASE_URL+'/api/user/save',
				processData: false,
				transformRequest: function (data) {
					var formData = new FormData();
					formData.append("foto", $scope.data.foto);
					formData.append("data", angular.toJson($scope.data));
					return formData;  
				},
				data : $scope.data,
				headers: { 'Content-Type': undefined }
			})
			.success(function(res){
				var x = angular.copy(o);
				x.save = 'update';
				$modalInstance.close(x);
			})
			.error(function(res) {
	    		swal('Exception', res.message);
	    	});
		}
	};
	$scope.uploadedFile = function(element) {
		$scope.currentFile = element.files[0];
		console.log('uploading... ');
		console.log($scope.currentFile);
	    var reader = new FileReader();
	    reader.onload = function(event) {
	    	$scope.image_source = event.target.result
	    	$scope.$apply(function($scope) {
	    		$scope.files = element.files;
	    	});
	    }
	    reader.readAsDataURL(element.files[0]);
	}
}])
;
