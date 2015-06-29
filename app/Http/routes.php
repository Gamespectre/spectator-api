<?php

Route::get('/', function() {
	return "Please use an endpoint";
});

Route::controller('games', 'GameController');
Route::controller('videos', 'VideoController');
Route::controller('series', 'SeriesController');
