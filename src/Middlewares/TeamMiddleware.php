<?php

namespace BigFiveEdition\Permission\Middlewares;

use BigFiveEdition\Permission\Exceptions\UnauthorizedException;
use Closure;
use Exception;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class TeamMiddleware
{
	public function handle($request, Closure $next, $team, $guard = null)
	{
		$authGuard = Auth::guard($guard);

		if ($authGuard->guest()) {
			throw UnauthorizedException::notLoggedIn();
		}

		$teams = is_array($team) ? $team : explode('|', $team);

		try {
//			$user = $authGuard->user();
			$user = $request->user();
			if (!$user->belongsToAnyTeams($teams)) {
				throw UnauthorizedException::forTeams($teams);
			}
		} catch (Exception $e) {
			Log::error($e->getMessage());
			Log::error($e->getTraceAsString());
		}

		return $next($request);
	}
}
