<?php

use Illuminate\Support\Facades\Route;

$prefix = config('bfe-permission.routes_prefix', '');
Route::group([
	'prefix' => "{$prefix}/bfe-permissions/",
	'middleware' => []
], function () {
	Route::apiResource('test', 'BigFiveEdition\Permission\Http\Controllers\Test\TestController');

	Route::apiResource('teams', 'BigFiveEdition\Permission\Http\Controllers\Team\TeamController');
	Route::group([
		'prefix' => 'teams/{team_id}/models',
		'middleware' => [],
		'namespace' => 'BigFiveEdition\Permission\Http\Controllers\TeamModel',
	], function () {
//		Route::apiResource('models', 'BigFiveEdition\Permission\Http\Controllers\TeamModel\TeamModelController');
		Route::get('/', 'TeamModelController@index');
		Route::get('/{team_model_id}', 'TeamModelController@show');
		Route::post('/', 'TeamModelController@store');
		Route::put('/{team_model_id}', 'TeamModelController@update');
		Route::delete('/{team_model_id}', 'TeamModelController@destroy');
	});
	Route::group([
		'prefix' => 'team-models',
		'middleware' => [],
		'namespace' => 'BigFiveEdition\Permission\Http\Controllers\TeamModel',
	], function () {
		Route::get('/', 'TeamModelController@index');
		Route::get('/{team_model_id}', 'TeamModelController@show');
		Route::post('/', 'TeamModelController@store');
		Route::put('/{team_model_id}', 'TeamModelController@update');
		Route::delete('/{team_model_id}', 'TeamModelController@destroy');
	});

	Route::apiResource('roles', 'BigFiveEdition\Permission\Http\Controllers\Role\RoleController');
	Route::group([
		'prefix' => 'roles/{role_id}/models',
		'middleware' => [],
		'namespace' => 'BigFiveEdition\Permission\Http\Controllers\RoleModel',
	], function () {
//		Route::apiResource('models', 'BigFiveEdition\Permission\Http\Controllers\RoleModel\RoleModelController');
		Route::get('/', 'RoleModelController@index');
		Route::get('/{role_model_id}', 'RoleModelController@show');
		Route::post('/', 'RoleModelController@store');
		Route::put('/{role_model_id}', 'RoleModelController@update');
		Route::delete('/{role_model_id}', 'RoleModelController@destroy');
	});
	Route::group([
		'prefix' => 'role-models',
		'middleware' => [],
		'namespace' => 'BigFiveEdition\Permission\Http\Controllers\RoleModel',
	], function () {
		Route::get('/', 'RoleModelController@index');
		Route::get('/{role_model_id}', 'RoleModelController@show');
		Route::post('/', 'RoleModelController@store');
		Route::put('/{role_model_id}', 'RoleModelController@update');
		Route::delete('/{role_model_id}', 'RoleModelController@destroy');
	});

	Route::apiResource('abilities', 'BigFiveEdition\Permission\Http\Controllers\Ability\AbilityController');
	Route::group([
		'prefix' => 'abilities/{ability_id}/models',
		'middleware' => [],
		'namespace' => 'BigFiveEdition\Permission\Http\Controllers\AbilityModel',
	], function () {
//		Route::apiResource('models', 'BigFiveEdition\Permission\Http\Controllers\AbilityModel\AbilityModelController');
		Route::get('/', 'AbilityModelController@index');
		Route::get('/{ability_model_id}', 'AbilityModelController@show');
		Route::post('/', 'AbilityModelController@store');
		Route::put('/{ability_model_id}', 'AbilityModelController@update');
		Route::delete('/{ability_model_id}', 'AbilityModelController@destroy');
	});
	Route::group([
		'prefix' => 'ability-models',
		'middleware' => [],
		'namespace' => 'BigFiveEdition\Permission\Http\Controllers\AbilityModel',
	], function () {
		Route::get('/', 'AbilityModelController@index');
		Route::get('/{ability_model_id}', 'AbilityModelController@show');
		Route::post('/', 'AbilityModelController@store');
		Route::put('/{ability_model_id}', 'AbilityModelController@update');
		Route::delete('/{ability_model_id}', 'AbilityModelController@destroy');
	});
});

/* Public________________________________________________________________ */
Route::group([
	'prefix' => "{$prefix}/bfe-permissions/",
], function () {
});

