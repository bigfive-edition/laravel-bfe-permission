<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::group([
	'middleware' => []
], function () {
	Route::apiResource('test', 'BigFiveEdition\Permission\Http\Controllers\Test\TestController');
});

/* Public________________________________________________________________ */
Route::group([
], function () {
});

