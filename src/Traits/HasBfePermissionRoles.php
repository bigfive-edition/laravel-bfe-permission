<?php

namespace BigFiveEdition\Permission\Traits;

use BigFiveEdition\Permission\Models\Role;
use BigFiveEdition\Permission\Models\RoleModel;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\Relations\MorphToMany;
use Illuminate\Support\Collection;

trait HasBfePermissionRoles
{
	public function role_models(): MorphMany
	{
		return $this->morphMany(RoleModel::class, 'model');
	}

	public function roles(): MorphToMany
	{
		return $this->morphToMany(Role::class, 'model', 'bfe_permission_model_has_roles', 'model_id', 'role_id');
	}

	public function addRoles(...$roles): Collection|array|null
	{
		return null;
	}

	public function removeRoles(...$roles): Collection|array|null
	{
		return null;
	}

	public function syncRoles(...$roles): Collection|array|null
	{
		return null;
	}

	public function hasAnyRoles(...$roles): bool
	{
		return false;
	}

	public function hasAllRoles(...$roles): bool
	{
		return false;
	}

}
