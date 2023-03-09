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

//		$user = $authGuard->user();
		$user = $request->user();

		if (stripos($team, '|')) {
			$teams = is_array($team) ? $team : explode('|', $team);
			if (!$user->belongsToAnyTeams($teams)) {
				throw UnauthorizedException::forTeams($teams);
			}
		}

		if (stripos($team, '&')) {
			$teams = is_array($team) ? $team : explode('&', $team);
			if (!$user->belongsToAllTeams($teams)) {
				throw UnauthorizedException::forTeams($teams);
			}
		}

		return $next($request);
	}
}
