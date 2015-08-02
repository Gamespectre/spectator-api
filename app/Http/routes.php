<?php

use Illuminate\Routing\Router;

Route::group(['middleware' => ['cors']], function(Router $router) {
	$router->get('auth/init', 'Auth\AuthController@startLogin');
	$router->get('auth/youtube', 'Auth\AuthController@redirectToProvider');
	$router->get('auth/oauth_callback', 'Auth\AuthController@handleProviderCallback');
	$router->get('auth/token', 'Auth\AuthController@token');

    $router->controller('admin', 'AdminController');

    $router->group(['middleware' => ['jwt.auth']], function(Router $router) {
		$router->get('auth/query', 'Auth\AuthController@query');
		$router->controller('video', 'VideoController');
		$router->controller('game', 'GameController');
		$router->controller('series', 'SeriesController');
		$router->controller('creator', 'CreatorController');
		$router->controller('search', 'SearchController');
	});
});

Route::get('/', function() {
    return "Please use an endpoint.";
});