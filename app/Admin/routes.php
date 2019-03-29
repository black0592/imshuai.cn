<?php

use Illuminate\Routing\Router;

Admin::registerAuthRoutes();

Route::group([
    'prefix'        => config('admin.route.prefix'),
    'namespace'     => config('admin.route.namespace'),
    'middleware'    => config('admin.route.middleware'),
], function (Router $router) {

    $router->get('/', 'HomeController@index');
    $router->resource('users', UserController::class);
    $router->resource('categories', CategoriesController::class);
    $router->resource('navs', NavController::class);
    $router->resource('menus', MenuController::class);
    $router->resource('topic', TopicController::class);

});
