<?php

Route::get('/', function() {
	return "Please use an endpoint";
});

Route::controller('test', 'TestController');
Route::controller('video', 'VideoController');
