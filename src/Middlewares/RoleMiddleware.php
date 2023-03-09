<?php

namespace BigFiveEdition\Permission\Middlewares;

use BigFiveEdition\Permission\Exceptions\UnauthorizedException;
use Closure;
use Exception;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class RoleMiddleware
{
	public function handle($request, Closure $next, $role, $guard = null)
	{
		$authGuard = Auth::guard($guard);

		if ($authGuard->guest()) {
			throw UnauthorizedException::notLoggedIn();
		}

		$roles = is_array($role) ? $role : explode('|', $role);

//		$user = $authGuard->user();
		$user = $request->user();
		if (!$user->hasAnyRoles($roles)) {
			throw UnauthorizedException::forRoles($roles);
		}

		return $next($request);
	}
}
