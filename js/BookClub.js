var BookClub = angular.module("BookClub", ['ngRoute']);

BookClub.config(['$routeProvider',
  function($routeProvider) {
    $routeProvider.
      when('/BookList', {
        templateUrl: 'BookList.html',
        controller: 'BooklistController'
      }).
	  when('/AddBook', {
        templateUrl: 'AddBook.html',
        controller: 'AddBookController'
      }).
      otherwise({
        redirectTo: '/BookList'
      });
  }]);

 var RESTUrl = "http://xubuntu-vm/BookClub/BookClubREST.php";

BookClub.controller('BooklistController', ['$scope','$http','$location','$filter', function($scope, $http,$location,$filter) {
	var d = new Date();
	$scope.StartDate =new Date(d.getFullYear(),d.getMonth(),1);
	if (d.getMonth() <11)
		$scope.EndDate =new Date(d.getFullYear(),d.getMonth()+1,0);
	else
		$scope.EndDate =new Date(d.getFullYear(),1,0);

	$scope.LoadBooks = function() {
		$http.get(RESTUrl, { params: { StartDate:$scope.StartDate, EndDate: $scope.EndDate}  } ).success(function(data) {	
			angular.forEach(data,function(el,i){
			  //fix date
			  el.NominationDate = new Date(el.NominationDate);
			});

            $scope.nominations = data;
		});
	};
	
	 var orderBy = $filter('orderBy');
	 $scope.predicate = 'NominationDate';

	
	$scope.order = function(predicate, reverse) {
	$scope.nominations = orderBy($scope.nominations, predicate, reverse);
	};
	
	$scope.LoadBooks();
	$scope.order('NominationDate',true);
	
    
	$scope.go = function ( path ) {
		$location.path( path );
	};

		
	
}]);

BookClub.controller('AddBookController', ['$scope','$location','$http', function($scope, $location,$http) {
	$scope.AddBookClick = function() {		
		$http({
			method: 'POST',
			url:RESTUrl,
			data: "title=" + encodeURIComponent($scope.title) + "&author=" + encodeURIComponent($scope.author) +  "&description=" + encodeURIComponent($scope.description) +"&nominatedby=" + encodeURIComponent($scope.nominatedBy) + "&nominatedDate=" + encodeURIComponent(JSON.stringify($scope.nominatedDate)),
			headers: {'Content-Type': 'application/x-www-form-urlencoded'}
			}).success( function () { $location.path('BookList');});
	};
	$scope.go = function ( path ) {
		$location.path( path );
	};
	
	
}]);