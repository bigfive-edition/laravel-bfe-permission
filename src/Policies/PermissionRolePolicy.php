<?php

namespace BigFiveEdition\Permission\Policies;

use BigFiveEdition\Permission\Exceptions\BfeUnauthorizedException;
use BigFiveEdition\Permission\Traits\HasBfePermissionRoles;
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
	 * @throws BfeUnauthorizedException
	 */
	public function hasRole($user, $role): Response|bool
	{
		$isAuthorized = false;
		$isAndOperation = false;
		$roles = [];

		//get roles
		if (stripos($role, '|')) {
			$roles = is_array($role) ? $role : array_map('trim', explode('|', $role));
		} else if (stripos($role, '&')) {
			$isAndOperation = true;
			$roles = is_array($role) ? $role : array_map('trim', explode('&', $role));
		} else {
			$roles = is_array($role) ? $role : [$role];
		}

		$models = [$user];

		//loop through models with abilities
		foreach ($models as $model) {
			try {
				if (in_array(HasBfePermissionRoles::class, class_uses_recursive(get_class($model)), true)) {
					if($isAndOperation) {
						$isAuthorized = $model->hasAllRoles($roles);
					}else {
						$isAuthorized = $model->hasAnyRoles($roles);
					}
				}
			} catch (Exception $e) {
				Log::error($e->getMessage());
				Log::error($e->getTraceAsString());
			}

			//if is authorized stop checking
			if ($isAuthorized) {
				break;
			}
		}

//		return $isAuthorized;
		return $isAuthorized ? Response::allow() : throw new BfeUnauthorizedException('You do not have the required roles to perform operation.');
//		return $isAuthorized ? Response::allow() : Response::deny('You do not have the required roles to perform operation.');
	}
}
