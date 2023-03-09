<?php

namespace BigFiveEdition\Permission\Middlewares;

use BigFiveEdition\Permission\Exceptions\UnauthorizedException;
use Closure;
use Illuminate\Support\Facades\Auth;

class AbilityMiddleware
{
	public function handle($request, Closure $next, $ability, $resourceType = null, $resourceId = null, $guard = null)
	{
		$authGuard = Auth::guard($guard);

		if ($authGuard->guest()) {
			throw UnauthorizedException::notLoggedIn();
		}

		$abilities = is_array($ability) ? $ability : explode('|', $ability);
		$type = $resourceType ? trim($resourceType) : null;
		$id = $resourceId ? trim($resourceId) : null;

//		$user = $authGuard->user();
		$user = $request->user();
		if (stripos($ability, '|') && !$user->hasAnyAbilitiesOn($abilities, $type, $id)) {
			throw UnauthorizedException::forAbilities($abilities);
		}
		if (stripos($ability, '&') && !$user->hasAllAbilitiesOn($abilities, $type, $id)) {
			throw UnauthorizedException::forAbilities($abilities);
		}

		return $next($request);
	}
}
