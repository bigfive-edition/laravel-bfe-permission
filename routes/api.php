<?php

use Illuminate\Support\Facades\Route;

$prefix = config('bfe-permission.routes_prefix', '');
Route::group([
	'prefix' => "{$prefix}/bfe-permissions/",
	'middleware' => []
], function () {
	Route::apiResource('test', 'BigFiveEdition\Permission\Http\Controllers\Test\TestController');

	Route::apiResource('teams', 'BigFiveEdition\Permission\Http\Controllers\Team\TeamController');
	Route::group(['prefix' => 'teams/{team_id}'], function () {
		Route::apiResource('models', 'BigFiveEdition\Permission\Http\Controllers\TeamModel\TeamModelController');
	});

	Route::apiResource('roles', 'BigFiveEdition\Permission\Http\Controllers\Role\RoleController');
	Route::group(['prefix' => 'roles/{role_id}'], function () {
		Route::apiResource('models', 'BigFiveEdition\Permission\Http\Controllers\RoleModel\RoleModelController');
	});

	Route::apiResource('abilities', 'BigFiveEdition\Permission\Http\Controllers\Ability\AbilityController');
	Route::group(['prefix' => 'abilities/{ability_id}'], function () {
		Route::apiResource('models', 'BigFiveEdition\Permission\Http\Controllers\AbilityModel\AbilityModelController');
	});
});

/* Public________________________________________________________________ */
Route::group([
	'prefix' => "{$prefix}/bfe-permissions/",
], function () {
});

