<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

$prefix = config('bfe-permission.routes_prefix', '');
Route::group([
	'prefix' => "{$prefix}/bfe-permissions/",
	'middleware' => []
], function () {
	Route::apiResource('test', 'BigFiveEdition\Permission\Http\Controllers\Test\TestController');
});

/* Public________________________________________________________________ */
Route::group([
	'prefix' => "{$prefix}/bfe-permissions/",
], function () {
});

