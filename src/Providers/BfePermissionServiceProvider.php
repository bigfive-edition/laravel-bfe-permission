<?php

namespace BigFiveEdition\Permission\Providers;

use BigFiveEdition\Permission\Commands\CreateBfePermissionAbility;
use BigFiveEdition\Permission\Commands\CreateBfePermissionRole;
use BigFiveEdition\Permission\Commands\CreateBfePermissionTeam;
use BigFiveEdition\Permission\Commands\GenerateBfePermissionAbilities;
use BigFiveEdition\Permission\Commands\GenerateBfePermissionRoles;
use BigFiveEdition\Permission\Commands\GenerateBfePermissionTeams;
use BigFiveEdition\Permission\Commands\InstallBfePermission;
use BigFiveEdition\Permission\Middlewares\AbilityMiddleware;
use BigFiveEdition\Permission\Middlewares\RoleMiddleware;
use BigFiveEdition\Permission\Middlewares\TeamMiddleware;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Routing\Route;
use Illuminate\Support\Collection;
use Illuminate\Support\ServiceProvider;
use Illuminate\View\Compilers\BladeCompiler;

class BfePermissionServiceProvider extends ServiceProvider
{
	public function boot(BfePermissionRegistrar $permissionLoader)
	{
		$this->offerPublishing();

		$this->registerMacroHelpers();

		$this->registerCommands();

		$this->registerModelBindings();

		$this->registerRoutes();
		$this->registerRouteMiddlewares();

		$this->app->singleton(BfePermissionRegistrar::class, function ($app) use ($permissionLoader) {
			return $permissionLoader;
		});
	}

	protected function offerPublishing()
	{
		if (!function_exists('config_path')) {
			// function not available and 'publish' not relevant in Lumen
			return;
		}

		$this->publishes([
			__DIR__ . '/../../config/bfe-permission.php' => config_path('bfe-permission.php'),
		], 'bfe-permission-config');

		$this->publishes([
			__DIR__ . '/../../database/migrations/create_bfe_permission_tables.php.stub' => $this->getMigrationFileName('create_bfe_permission_tables.php'),
		], 'bfe-permission-migrations');
	}

	protected function getMigrationFileName($migrationFileName): string
	{
		$timestamp = date('Y_m_d_His');

		$filesystem = $this->app->make(Filesystem::class);

		return Collection::make($this->app->databasePath() . DIRECTORY_SEPARATOR . 'migrations' . DIRECTORY_SEPARATOR)
			->flatMap(function ($path) use ($filesystem, $migrationFileName) {
				return $filesystem->glob($path . '*_' . $migrationFileName);
			})
			->push($this->app->databasePath() . "/migrations/{$timestamp}_{$migrationFileName}")
			->first();
	}

	protected function registerMacroHelpers()
	{
		if (!method_exists(Route::class, 'macro')) { // Lumen
			return;
		}
	}

	protected function registerCommands()
	{
		$this->commands([
			InstallBfePermission::class,
			GenerateBfePermissionTeams::class,
			GenerateBfePermissionRoles::class,
			GenerateBfePermissionAbilities::class,
//			RunBfePermissionSeeders::class,
			CreateBfePermissionTeam::class,
			CreateBfePermissionRole::class,
			CreateBfePermissionAbility::class,
		]);
	}

	protected function registerModelBindings()
	{
//		$config = $this->app->config['bfe-permission.models'];
//
//		if (!$config) {
//			return;
//		}
	}

	protected function registerRoutes()
	{
		$this->loadRoutesFrom(__DIR__ . '/../../routes/api.php');
	}

	protected function registerRouteMiddlewares()
	{
		app('router')->aliasMiddleware('bfe-permission.teams', TeamMiddleware::class);
		app('router')->aliasMiddleware('bfe-permission.roles', RoleMiddleware::class);
		app('router')->aliasMiddleware('bfe-permission.abilities', AbilityMiddleware::class);
	}

	public function register()
	{
		$this->mergeConfigFrom(
			__DIR__ . '/../../config/bfe-permission.php',
			'bfe-permission'
		);

		$this->callAfterResolving('blade.compiler', function (BladeCompiler $bladeCompiler) {
			$this->registerBladeExtensions($bladeCompiler);
		});
	}

	protected function registerBladeExtensions($bladeCompiler)
	{

	}
}
