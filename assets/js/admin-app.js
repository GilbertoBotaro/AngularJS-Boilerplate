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
    return $resource( admin_app_local.api_url + 'wp/v2/book/:id', {
        id: '@id'
    },{
        'query':{
            method: 'GET',
            isArray: true,
            url: admin_app_local.api_url + 'wp/v2/book?filter[posts_per_page]=-1',
        },
        'update':{
            method:'POST',
            headers: {
                'X-WP-Nonce': admin_app_local.nonce
            }
        },
        'post':{
            method:'POST',
            headers: {
                'X-WP-Nonce': admin_app_local.nonce
            }
        },
        'save':{
            method:'POST',
            headers: {
                'X-WP-Nonce': admin_app_local.nonce
            }
        },
        'delete':{
            method:'DELETE',
            headers: {
                'X-WP-Nonce': admin_app_local.nonce
            }
        }
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

    $scope.deletePost = function( key, id ) {
        var conf = confirm( 'Are you sure you want to delete this post?' );
        if( conf ){
            Books.delete({id:id}, function(res){
                if( res.delete )
                    $scope.books.splice(key, 1);
            });
        }
    }

}]);

/*
 * Detail Controller - loads on detail state
 */
admin_js_app.app.controller( 'DetailController', ['$scope', '$rootScope', 'Books', '$stateParams', function( $scope, $rootScope, Books, $stateParams ){

    console.log( 'loading detail view for ID ' + $stateParams.id );

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

        if( !$scope.book.meta.isbn )
            $scope.book.meta.isbn = [];
        if( !$scope.book.meta.price )
            $scope.book.meta.price = [];
    });

    $scope.savePost = function(){
        /*
         * Send back appropriate post object
         * book.content, book.title, book.excerpt
         */
        var content = $scope.book.content.rendered;
        $scope.book.content = content;
        var title = $scope.book.title.rendered;
        $scope.book.title = title;
        var excerpt = $scope.book.excerpt.rendered;
        $scope.book.excerpt = excerpt;

        Books.update($scope.book, function(res){
            $scope.book = res;
        });

    }

}]);