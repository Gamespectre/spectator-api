<?php

use Illuminate\Routing\Router;

// Authentication routes...
Route::get('auth/login', 'Auth\AuthController@getLogin');
Route::post('auth/login', 'Auth\AuthController@postLogin');
Route::get('auth/logout', 'Auth\AuthController@getLogout');

Route::get('/', function() {
	return "Please use an endpoint.";
});

Route::group(['middleware' => 'cors'], function(Router $router) {
	$router->controller('admin', 'AdminController');
	$router->controller('video', 'VideoController');
	$router->controller('game', 'GameController');
	$router->controller('series', 'SeriesController');
	$router->controller('creator', 'CreatorController');
	$router->controller('search', 'SearchController');
});