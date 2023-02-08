<?php

namespace BigFiveEdition\Permission\Middlewares;

use Closure;
use Illuminate\Support\Facades\Auth;

class RoleMiddleware
{
	public function handle($request, Closure $next, $role, $guard = null)
	{
		$authGuard = Auth::guard($guard);

		if ($authGuard->guest()) {
			throw UnauthorizedException::notLoggedIn();
		}

		$roles = is_array($role)
			? $role
			: explode('|', $role);

		if (!$authGuard->user()->hasAnyRoles($roles)) {
			throw UnauthorizedException::forRoles($roles);
		}

		return $next($request);
	}
}
