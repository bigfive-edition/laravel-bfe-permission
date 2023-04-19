<?php

namespace BigFiveEdition\Permission\Middlewares;

use BigFiveEdition\Permission\Exceptions\BfeUnauthorizedException;
use BigFiveEdition\Permission\Traits\BelongsToBfePermissionTeams;
use BigFiveEdition\Permission\Traits\HasBfePermissionAbilities;
use BigFiveEdition\Permission\Traits\HasBfePermissionRoles;
use Closure;
use Exception;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class AbilityMiddleware
{
	public function handle($request, Closure $next, $ability, $resourceType = null, $resourceId = null, $guard = null)
	{
		$authGuard = Auth::guard($guard);

		if ($authGuard->guest()) {
			throw BfeUnauthorizedException::notLoggedIn();
		}
		//$user = $authGuard->user();
		$user = $request->user();

		$isAuthorized = false;
		$isAndOperation = false;
		$type = $resourceType ? trim($resourceType) : null;
		$id = $resourceId ? trim($resourceId) : null;
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

		if (!$isAuthorized) {
			throw BfeUnauthorizedException::forAbilities($abilities);
		}

		return $next($request);
	}
}
