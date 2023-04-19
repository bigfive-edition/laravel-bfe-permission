<?php

namespace BigFiveEdition\Permission\Policies;

use BigFiveEdition\Permission\Exceptions\BfeUnauthorizedException;
use BigFiveEdition\Permission\Traits\BelongsToBfePermissionTeams;
use BigFiveEdition\Permission\Traits\HasBfePermissionAbilities;
use BigFiveEdition\Permission\Traits\HasBfePermissionRoles;
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
	 * @throws BfeUnauthorizedException
	 */
	public function hasAbility($user, $ability, $resource = null): Response|bool
	{
		$isAuthorized = false;
		$isAndOperation = false;
		$type = $resource != null && is_object($resource) ? get_class($resource) : null;
		$id = $resource != null && is_object($resource) ? Arr::get($resource, 'id') : null;
		$abilities = [];

		//get abilities
		if (stripos($ability, '|')) {
			$abilities = is_array($ability) ? $ability : explode('|', $ability);
		} else if (stripos($ability, '&')) {
			$isAndOperation = true;
			$abilities = is_array($ability) ? $ability : explode('&', $ability);
		} else {
			$abilities = is_array($ability) ? $ability : [$ability];
		}

		$models = [$user];

		//get related/parents models
		$models = [];
		if (in_array(HasBfePermissionRoles::class, class_uses_recursive(get_class($user)), true)) {
			$models = array_merge($models, $user->roles->all());
		}
		if (in_array(BelongsToBfePermissionTeams::class, class_uses_recursive(get_class($user)), true)) {
			$models = array_merge($models, $user->teams->all());
		}
		$models[] = $user;

		//loop through models with abilities
		foreach ($models as $model) {
			try {
				if (in_array(HasBfePermissionAbilities::class, class_uses_recursive(get_class($model)), true)) {
					Log::debug('ability check', [
						'model' => get_class($model),
						'required' => $abilities,
						'given' => $model->abilities()->pluck('slug')->all(),
					]);
					//check if has wildcard ability
					if ($model->hasAllAbilitiesOn(["*"], $type, $id)) {
						$isAuthorized = true;
						break;
					}

					if ($isAndOperation) {
						$isAuthorized = $model->hasAllAbilitiesOn($abilities, $type, $id);
					} else {
						$isAuthorized = $model->hasAnyAbilitiesOn($abilities, $type, $id);
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
		return $isAuthorized ? Response::allow() : throw new BfeUnauthorizedException('You do not have the required abilities to perform operation.');
//		return $isAuthorized ? Response::allow() : Response::deny('You do not have the required abilities to perform operation.');
	}

	/**
	 * Determine whether the model has the passed ability on the resource
	 *
	 * @param  $user
	 * @param  $resource
	 * @return Response|bool
	 * @throws BfeUnauthorizedException
	 */
	public function hasAbilityOnResource($user, $resource = null): Response|bool
	{
		$isAuthorized = false;
		$isAndOperation = false;
		$ability = $this->abilities ?? '';
		$type = $resource != null && is_object($resource) ? get_class($resource) : null;
		$id = $resource != null && is_object($resource) ? Arr::get($resource, 'id') : null;
		$abilities = [];

		//get abilities
		if (stripos($ability, '|')) {
			$abilities = is_array($ability) ? $ability : explode('|', $ability);
		} else if (stripos($ability, '&')) {
			$isAndOperation = true;
			$abilities = is_array($ability) ? $ability : explode('&', $ability);
		} else {
			$abilities = is_array($ability) ? $ability : [$ability];
		}

		$models = [$user];

		//get related/parents models
		$models = [];
		if (in_array(HasBfePermissionRoles::class, class_uses_recursive(get_class($user)), true)) {
			$models = array_merge($models, $user->roles->all());
		}
		if (in_array(BelongsToBfePermissionTeams::class, class_uses_recursive(get_class($user)), true)) {
			$models = array_merge($models, $user->teams->all());
		}
		$models[] = $user;

		//loop through models with abilities
		foreach ($models as $model) {
			try {
				if (in_array(HasBfePermissionAbilities::class, class_uses_recursive(get_class($model)), true)) {
					Log::debug('ability check', [
						'model' => get_class($model),
						'required' => $abilities,
						'given' => $model->abilities()->pluck('slug')->all(),
					]);
					//check if has wildcard ability
					if ($model->hasAllAbilitiesOn(["*"], $type, $id)) {
						$isAuthorized = true;
						break;
					}

					if ($isAndOperation) {
						$isAuthorized = $model->hasAllAbilitiesOn($abilities, $type, $id);
					} else {
						$isAuthorized = $model->hasAnyAbilitiesOn($abilities, $type, $id);
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
		return $isAuthorized ? Response::allow() : throw new BfeUnauthorizedException('You do not have the required abilities to perform operation.');
//		return $isAuthorized ? Response::allow() : Response::deny('You do not have the required abilities to perform operation.');
	}
}
