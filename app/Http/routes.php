<?php

Route::get('/', function() {
	return "Please use an endpoint";
});

Route::controller('test', 'TestController');
Route::controller('video', 'VideoController');
Route::controller('game', 'GameController');
Route::controller('series', 'SeriesController');
Route::controller('creator', 'CreatorController');
Route::controller('search', 'SearchController');
