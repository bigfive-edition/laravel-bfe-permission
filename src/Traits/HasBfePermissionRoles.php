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

	public function addRoles($roles): Collection|array|null
	{
		$roleIds = Role::query()
			->whereIn('slug', $roles)
			->pluck('id')->all();

		$this->role_models()->saveMany(collect($roleIds)->map(function ($roleId) {
			return RoleModel::firstOrCreate(
				[
					'role_id' => $roleId,
					'model_id' => $this->id,
					'model_type' => $this->getMorphClass(),
				]);
		}));
		$this->refresh();

		return $this->roles;
	}

	public function removeRoles($roles): Collection|array|null
	{
		$roleIds = Role::query()
			->whereIn('slug', $roles)
			->pluck('id')->all();

		$this->roles()->detach($roleIds);
		$this->refresh();

		return $this->roles;
	}

	public function syncRoles($roles): Collection|array|null
	{
		$roleIds = Role::query()
			->whereIn('slug', $roles)
			->pluck('id')->all();

		$this->roles()->sync($roleIds);
		$this->refresh();

		return $this->roles;
	}

	public function hasAnyRoles($roles): bool
	{
		return $this->roles()
				->whereIn('slug', $roles)
				->count() > 0;
	}

	public function hasAllRoles($roles): bool
	{
		$count = $this->roles()
			->whereIn('slug', $roles)
			->count();

		return $count >= count($roles);
	}

}
