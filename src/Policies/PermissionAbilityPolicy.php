<?php

namespace BigFiveEdition\Permission\Policies;

use Exception;
use Illuminate\Auth\Access\HandlesAuthorization;
use Illuminate\Auth\Access\Response;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Log;

class PermissionAbilityPolicy
{
	use HandlesAuthorization;

	protected $abilities;

	public function before($user, $abilities)
	{
		$this->abilities = $abilities;
	}

	/**
	 * Determine whether the model has the passed ability on the resource
	 *
	 * @param  $user
	 * @param  $ability
	 * @param  $resource
	 * @return Response|bool
	 */
	public function hasAbility($user, $ability, $resource = null): Response|bool
	{
		try {
			$type = $resource ? get_class($resource) : null;
			$id = $resource ? Arr::get($resource, 'id') : null;

			if (stripos($ability, '|')) {
				$abilities = is_array($ability) ? $ability : explode('|', $ability);
				return $user->hasAnyAbilitiesOn($abilities, $type, $id);
			} else if (stripos($ability, '&')) {
				$abilities = is_array($ability) ? $ability : explode('&', $ability);
				return $user->hasAllAbilitiesOn($abilities, $type, $id);
			} else {
				$abilities = is_array($ability) ? $ability : [$ability];
				return $user->hasAnyAbilitiesOn($abilities, $type, $id);
			}
		} catch (Exception $e) {
			Log::error($e->getMessage());
			Log::error($e->getTraceAsString());
		}
		return false;
	}
}
