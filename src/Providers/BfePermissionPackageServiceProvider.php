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
use BigFiveEdition\Permission\Middlewares\LocaleMiddleware;
use BigFiveEdition\Permission\Middlewares\RoleMiddleware;
use BigFiveEdition\Permission\Middlewares\TeamMiddleware;
use BigFiveEdition\Permission\Models\Ability;
use BigFiveEdition\Permission\Policies\PermissionAbilityPolicy;
use BigFiveEdition\Permission\Policies\PermissionRolePolicy;
use BigFiveEdition\Permission\Policies\PermissionTeamPolicy;
use Exception;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\ServiceProvider;
use Prettus\Repository\Providers\RepositoryServiceProvider;

class BfePermissionPackageServiceProvider extends ServiceProvider
{
	private $packageName = 'bfe-permission';

	public function boot()
	{
		//Configurations
		if (!function_exists('config_path')) {
			// function not available and 'publish' not relevant in Lumen
			return;
		}
		$this->publishes([
			__DIR__ . '/../../config/repository.php' => config_path('repository.php'),
		], 'repository-config');

		$this->publishes([
			__DIR__ . '/../../config/bfe-permission.php' => config_path('bfe-permission.php'),
		], "{$this->packageName}-config");


		//Migrations
		$this->publishes([
			__DIR__ . '/../../database/migrations/create_bfe_permission_tables.php.stub' => $this->getMigrationFileName('2023_01_26_195737_create_bfe_permission_tables.php'),
		], "{$this->packageName}-migrations");

		$this->publishes([
			__DIR__ . '/../../database/migrations/add_bfe_permission_translations_tables.php.stub' => $this->getMigrationFileName('2023_08_02_195737_add_bfe_permission_translations_tables.php'),
		], "{$this->packageName}-migrations");
//		$this->publishesMigrations([
//			__DIR__.'/../../database/migrations' => database_path('migrations'),
//		]);


		//Languages
		$this->loadTranslationsFrom(__DIR__ . '/../../resources/lang', $this->packageName);
		$this->publishes([
			__DIR__ . '/../../resources/lang' => $this->app->langPath("vendor/{$this->packageName}"),
		]);

		//Views
		$this->loadViewsFrom(__DIR__ . '/../../resources/views', $this->packageName);
		$this->publishes([
			__DIR__ . '/../../resources/views' => resource_path("views/vendor/{$this->packageName}"),
		]);


		//Public
		$this->publishes([
			__DIR__ . '/../../public' => public_path("vendor/{$this->packageName}"),
		], 'public');


		//Routes
		$this->loadRoutesFrom(__DIR__ . '/../../routes/api.php');


		//Middlewares
		app('router')->aliasMiddleware('bfe-permission.locale', LocaleMiddleware::class);
		app('router')->aliasMiddleware('bfe-permission.teams', TeamMiddleware::class);
		app('router')->aliasMiddleware('bfe-permission.roles', RoleMiddleware::class);
		app('router')->aliasMiddleware('bfe-permission.abilities', AbilityMiddleware::class);


		//Policies
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


		//Commands
		if ($this->app->runningInConsole()) {
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
	}


	protected function getMigrationFileName($migrationFileName): string
	{
//		$timestamp = date('Y_m_d_His');
//		$filename = "{$timestamp}_{$migrationFileName}";
		$filename = "{$migrationFileName}";

		$filesystem = $this->app->make(Filesystem::class);

		return Collection::make($this->app->databasePath() . DIRECTORY_SEPARATOR . 'migrations' . DIRECTORY_SEPARATOR)
			->flatMap(function ($path) use ($filesystem, $migrationFileName) {
				return $filesystem->glob($path . '*_' . $migrationFileName);
			})
			->push($this->app->databasePath() . "/migrations/bfe-permissions/{$filename}")
			->first();
	}


	public function register()
	{
		$this->app->register(RepositoryServiceProvider::class);

		//Configurations
		$this->mergeConfigFrom(
			__DIR__ . '/../../config/bfe-permission.php',
			"{$this->packageName}"
		);
		$this->mergeConfigFrom(
			__DIR__ . '/../../config/repository.php',
			'repository'
		);

	}
}
