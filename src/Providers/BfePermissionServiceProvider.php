<?php

namespace BigFiveEdition\Permission\Providers;

use BigFiveEdition\Permission\Commands\InstallBfePermission;
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

		if ($this->app->config['bfe-permission.register_permission_check_method']) {
//			$permissionLoader->clearClassPermissions();
//			$permissionLoader->registerPermissions();
		}

		$this->app->singleton(BfePermissionRegistrar::class, function ($app) use ($permissionLoader) {
			return $permissionLoader;
		});
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

	protected function offerPublishing()
	{
		if (!function_exists('config_path')) {
			// function not available and 'publish' not relevant in Lumen
			return;
		}

		$this->publishes([
			__DIR__ . '/../../config/bfe-permission.php' => config_path('bfe-permission.php'),
		], 'bfe-permission-config');

//		$this->publishes([
//			__DIR__.'/../../database/migrations/create_bfe_permission_tables.php.stub' => $this->getMigrationFileName('create_bfe_permission_tables.php'),
//		], 'bfe-permission-migrations');
	}

	protected function registerCommands()
	{
		$this->commands([
			InstallBfePermission::class,
		]);
	}

	protected function registerModelBindings()
	{
		$config = $this->app->config['bfe-permission.models'];

		if (!$config) {
			return;
		}
	}

	public static function bladeMethodWrapper($method, $role, $guard = null)
	{

	}

	protected function registerBladeExtensions($bladeCompiler)
	{

	}

	protected function registerMacroHelpers()
	{
		if (!method_exists(Route::class, 'macro')) { // Lumen
			return;
		}
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
}
