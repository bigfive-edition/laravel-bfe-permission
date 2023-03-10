<?php

namespace BigFiveEdition\Permission\Policies;

use Exception;
use Illuminate\Auth\Access\HandlesAuthorization;
use Illuminate\Auth\Access\Response;
use Illuminate\Support\Facades\Log;

class PermissionRolePolicy
{
	use HandlesAuthorization;

	protected $abilities;

	public function before($user, $abilities)
	{
		$this->abilities = $abilities;
	}

	/**
	 * Determine whether the model has the passed role
	 *
	 * @param $user
	 * @param $role
	 * @return Response|bool
	 */
	public function hasRole($user, $role): Response|bool
	{
		$allowed = false;
		try {
			if (stripos($role, '|')) {
				$roles = is_array($role) ? $role : explode('|', $role);
				$allowed = $user->hasAnyRoles($roles);
			} else if (stripos($role, '&')) {
				$roles = is_array($role) ? $role : explode('&', $role);
				$allowed = $user->hasAllRoles($roles);
			} else {
				$roles = is_array($role) ? $role : [$role];
				$allowed = $user->hasAnyRoles($roles);
			}
		} catch (Exception $e) {
			Log::error($e->getMessage());
			Log::error($e->getTraceAsString());
		}
//		return $allowed;
		return $allowed ? Response::allow() : Response::deny('You do not have the required roles to perform operation.');
	}
}
