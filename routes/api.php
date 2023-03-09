<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

$prefix = config('bfe-permission.routes_prefix', '');
Route::group([
	'prefix' => "{$prefix}/bfe-permissions/",
	'middleware' => []
], function () {
	Route::apiResource('test', 'BigFiveEdition\Permission\Http\Controllers\Test\TestController');
	Route::apiResource('teams', 'BigFiveEdition\Permission\Http\Controllers\Team\TeamController');
	Route::apiResource('roles', 'BigFiveEdition\Permission\Http\Controllers\Role\RoleController');
	Route::apiResource('abilities', 'BigFiveEdition\Permission\Http\Controllers\Ability\AbilityController');
});

/* Public________________________________________________________________ */
Route::group([
	'prefix' => "{$prefix}/bfe-permissions/",
], function () {
});

