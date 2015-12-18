var admin_js_app = admin_js_app || {};

admin_js_app.app = angular.module( 'admin-js-app', ['ui.router', 'ngResource'] );

/*
 * UI Router States
 */
admin_js_app.app.config(function($stateProvider, $urlRouterProvider) {
    $urlRouterProvider.otherwise('/');
    $stateProvider
        .state('main', {
            url: '/',
            templateUrl: admin_app_local.template_directory + '/admin-js-app-main.html',
        })
        .state('list', {
            url: '/books',
            templateUrl: admin_app_local.template_directory + '/admin-js-app-list.html',
            controller: 'ListController'
        })
        .state('book', {
            url: '/book/:id',
            templateUrl: admin_app_local.template_directory + '/admin-js-app-detail.html',
            controller: 'DetailController'
        })
        .state('edit', {
            url: '/book/:id/edit',
            templateUrl: admin_app_local.template_directory + '/admin-js-app-edit.html',
            controller: 'EditController'
        })
});
/*
 * Filter to trust HTML
*/
admin_js_app.app.filter( 'to_trusted', function( $sce ){
    return function( text ){
        return $sce.trustAsHtml( text );
    }
});


/*
 * Book Factory - ties into /wp-json/js-admin-app/books/
 */
admin_js_app.app.factory( 'Books', function( $resource ){
    return $resource( admin_app_local.api_url + 'js-admin-app/books/:id', {
        id: '@id'
    });
} )


/*
 * List Controller - loads on List state
 */
admin_js_app.app.controller( 'ListController', ['$scope', '$rootScope', 'Books', function( $scope, $rootScope, Books ){

    console.log( 'loading list view...' );
    Books.query(function(res){
        $scope.books = res;
    })

}]);

/*
 * Detail Controller - loads on detail state
 */
admin_js_app.app.controller( 'DetailController', ['$scope', '$rootScope', 'Books', '$stateParams', function( $scope, $rootScope, Books, $stateParams ){

    console.log( 'loading detail view...' );

    Books.get({ id: $stateParams.id}, function(res){
        $scope.book = res;
    })

}]);

/*
 * Edit Controller - loads on edit state
 */
admin_js_app.app.controller( 'EditController', ['$scope', '$rootScope', 'Books', '$stateParams', function( $scope, $rootScope, Books, $stateParams ){

    console.log( 'loading edit view...' );

    Books.get({ id: $stateParams.id}, function(res){
        $scope.book = res;
        $scope.book.id = res.ID;
    });

    $scope.savePost = function(){
        Books.save($scope.book, function(res){
            $scope.book = res.post;
        });

    }

}]);