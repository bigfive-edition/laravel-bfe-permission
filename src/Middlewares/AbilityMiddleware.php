<?php

namespace BigFiveEdition\Permission\Middlewares;

use Closure;
use Illuminate\Support\Facades\Auth;

class AbilityMiddleware
{
	public function handle($request, Closure $next, $ability, $guard = null)
	{
		$authGuard = Auth::guard($guard);

		if ($authGuard->guest()) {
			throw UnauthorizedException::notLoggedIn();
		}

		$abilities = is_array($ability)
			? $ability
			: explode('|', $ability);

		if (! $authGuard->user()->hasAnyAbilities($abilities)) {
			throw UnauthorizedException::forAbilities($abilities);
		}

		return $next($request);
	}
}
