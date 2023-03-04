<?php

namespace BigFiveEdition\Permission\Policies;

use BigFiveEdition\Permission\Exceptions\UnauthorizedException;
use Illuminate\Auth\Access\HandlesAuthorization;

class PermissionRolePolicy
{
	use HandlesAuthorization;

	public function hasRole($model, $role): bool
	{
		if (stripos($role, '|')) {
			$roles = is_array($role) ? $role : explode('|', $role);
			return $model->hasAnyRoles($roles);
		}

		if (stripos($role, '&')) {
			$roles = is_array($role) ? $role : explode('&', $role);
			return $model->hasAllRoles($roles);
		}
		return false;
	}
}
