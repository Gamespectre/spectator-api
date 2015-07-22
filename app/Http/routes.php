<?php

use Illuminate\Routing\Router;

// Authentication routes...
Route::get('auth/init', 'Auth\AuthController@startLogin');
Route::get('auth/youtube', 'Auth\AuthController@redirectToProvider');
Route::get('auth/oauth_callback', 'Auth\AuthController@handleProviderCallback');
Route::get('auth/token', 'Auth\AuthController@token');

Route::group(['middleware' => 'jwt.refresh'], function(Router $router) {
    $router->get('auth/query', 'Auth\AuthController@query');
});

Route::group(['middleware' => 'cors'], function(Router $router) {
	$router->controller('admin', 'AdminController');
	$router->controller('video', 'VideoController');
	$router->controller('game', 'GameController');
	$router->controller('series', 'SeriesController');
	$router->controller('creator', 'CreatorController');
	$router->controller('search', 'SearchController');
});

Route::get('/', function() {
    return "Please use an endpoint.";
});