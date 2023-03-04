<?php

namespace BigFiveEdition\Permission\Providers;

use BigFiveEdition\Permission\Contracts\AbilityOperationType;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;

class BfePermissionAuthServiceProvider extends ServiceProvider
{
	/**
	 * The policy mappings for the application.
	 *
	 * @var array<class-string, class-string>
	 */
	protected $policies = [
		// 'App\Models\Model' => 'App\Policies\ModelPolicy',
	];

	public function register()
	{

	}

	public function boot()
	{
		$this->registerPolicies();
		$this->registerAuthGatesAndPolicies();
	}

	protected function registerAuthGatesAndPolicies()
	{
		$operations = config('bfe-permission.ability_operations', AbilityOperationType::ALL);

		Gate::define('bfe-permission-belongs-teams', 'BigFiveEdition\Permission\Policies\PermissionTeamPolicy@belongsToTeam');
		Gate::define('bfe-permission-has-roles', 'BigFiveEdition\Permission\Policies\PermissionRolePolicy@hasRole');
		Gate::define('bfe-permission-has-abilities', 'BigFiveEdition\Permission\Policies\PermissionAbilityPolicy@hasAbility');
	}
}

