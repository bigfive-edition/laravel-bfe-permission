<?php

namespace BigFiveEdition\Permission\Policies;

use Illuminate\Auth\Access\HandlesAuthorization;

class PermissionRolePolicy
{
	use HandlesAuthorization;

	public function hasRole($model, $role): bool
	{
		if (stripos($role, '|')) {
			$roles = is_array($role) ? $role : explode('|', $role);
			return $model->hasAnyRoles($roles);
		} else if (stripos($role, '&')) {
			$roles = is_array($role) ? $role : explode('&', $role);
			return $model->hasAllRoles($roles);
		} else {
			$roles = is_array($role) ? $role : [$role];
			return $model->hasAnyRoles($roles);
		}
		return false;
	}
}
