<?php

namespace BigFiveEdition\Permission\Middlewares;

use BigFiveEdition\Permission\Exceptions\UnauthorizedException;
use BigFiveEdition\Permission\Traits\HasBfePermissionAbilities;
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
			throw UnauthorizedException::notLoggedIn();
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
		foreach ($models as $model) {
			try {
				if (in_array(HasBfePermissionAbilities::class, class_uses_recursive(get_class($model)), true)) {
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
			throw UnauthorizedException::forAbilities($abilities);
		}

		return $next($request);
	}
}
