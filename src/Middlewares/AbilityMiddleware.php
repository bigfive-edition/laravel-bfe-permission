<?php

namespace BigFiveEdition\Permission\Middlewares;

use BigFiveEdition\Permission\Exceptions\UnauthorizedException;
use Closure;
use Exception;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class AbilityMiddleware
{
	public function handle($request, Closure $next, $ability, $guard = null)
	{
		$authGuard = Auth::guard($guard);

		if ($authGuard->guest()) {
			throw UnauthorizedException::notLoggedIn();
		}

		$abilities = is_array($ability) ? $ability : explode('|', $ability);

		try {
//			$user = $authGuard->user();
			$user = $request->user();
			if (!$user->hasAnyAbilitiesOn($abilities)) {
				throw UnauthorizedException::forAbilities($abilities);
			}
		} catch (Exception $e) {
			Log::error($e->getMessage());
			Log::error($e->getTraceAsString());
		}

		return $next($request);
	}
}
