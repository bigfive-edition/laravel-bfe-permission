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

//		$user = $authGuard->user();
		$user = $request->user();

		if (stripos($role, '|')) {
			$roles = is_array($role) ? $role : explode('|', $role);
			if (!$user->belongsToAnyRoles($roles)) {
				throw UnauthorizedException::forRoles($roles);
			}
		}

		if (stripos($role, '&')) {
			$roles = is_array($role) ? $role : explode('&', $role);
			if (!$user->belongsToAllRoles($roles)) {
				throw UnauthorizedException::forRoles($roles);
			}
		}

		return $next($request);
	}
}
