<?php

namespace BigFiveEdition\Permission\Traits;

use BigFiveEdition\Permission\Models\Role;
use BigFiveEdition\Permission\Models\RoleModel;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\Relations\MorphToMany;
use Illuminate\Support\Collection;

trait HasBfePermissionRoles
{
	public function addRoles(array $roles): Collection|array|null
	{
		$roleIds = Role::query()
			->whereIn('slug', $roles)
			->pluck('id')->all();

		$items = collect($roleIds)->map(function ($roleId) {
			return RoleModel::firstOrCreate(
				[
					'role_id' => $roleId,
					'model_id' => $this->id,
					'model_type' => $this->getMorphClass(),
				]);
		});
		$this->role_models()->saveMany($items);
		$this->refresh();

		return $this->roles;
	}

	public function role_models(): MorphMany
	{
		return $this->morphMany(RoleModel::class, 'model');
	}

	public function removeRoles(array $roles): Collection|array|null
	{
		$roleIds = Role::query()
			->whereIn('slug', $roles)
			->pluck('id')->all();

		$this->roles()->detach($roleIds);
		$this->refresh();

		return $this->roles;
	}

	public function roles(): MorphToMany
	{
		return $this->morphToMany(Role::class, 'model', 'bfe_permission_model_has_roles', 'model_id', 'role_id');
	}

	public function syncRoles(array $roles): Collection|array|null
	{
		$roleIds = Role::query()
			->whereIn('slug', $roles)
			->pluck('id')->all();

		$this->roles()->sync($roleIds);
		$this->refresh();

		return $this->roles;
	}

	public function hasAnyRoles(array $roles): bool
	{
		$count = $this->role_models()
			->whereHas('role', function ($query) use ($roles) {
				$query->whereIn('slug', $roles);
			})
			->count();

		return $count > 0;
	}

	public function hasAllRoles(array $roles): bool
	{
		$count = $this->role_models()
			->whereHas('role', function ($query) use ($roles) {
				$query->whereIn('slug', $roles);
			})
			->count();

		return $count >= count($roles);
	}

}
