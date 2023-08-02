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
use BigFiveEdition\Permission\Models\Ability;
use BigFiveEdition\Permission\Policies\PermissionAbilityPolicy;
use BigFiveEdition\Permission\Policies\PermissionRolePolicy;
use BigFiveEdition\Permission\Policies\PermissionTeamPolicy;
use Exception;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Routing\Route;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\ServiceProvider;
use Illuminate\View\Compilers\BladeCompiler;

class BfePermissionPackageServiceProvider extends ServiceProvider
{
	public function boot()
	{
		$this->offerPublishing();

		$this->registerMacroHelpers();

		$this->registerCommands();

		$this->registerModelBindings();

		$this->registerRoutes();
		$this->registerRouteMiddlewares();
		$this->registerAuthGatesAndPolicies();
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
		$this->publishes([
			__DIR__ . '/../../database/migrations/add_bfe_permission_translations_tables.php.stub' => $this->getMigrationFileName('add_bfe_permission_translations_tables.php'),
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
			->push($this->app->databasePath() . "/migrations/bfe-permissions/{$migrationFileName}")
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

	protected function registerAuthGatesAndPolicies()
	{
		Gate::define('bfe-permission-belongs-teams', [PermissionTeamPolicy::class, 'belongsToTeam']);
		Gate::define('bfe-permission-has-roles', [PermissionRolePolicy::class, 'hasRole']);
		Gate::define('bfe-permission-has-abilities', [PermissionAbilityPolicy::class, 'hasAbility']);

		try {
			$abilities = Ability::all();
			foreach ($abilities as $ability) {
				Gate::define($ability->slug, [PermissionAbilityPolicy::class, 'hasAbilityOnResource']);
			}
		} catch (Exception $e) {
			Log::error($e->getMessage());
			Log::error($e->getTraceAsString());
		}
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
