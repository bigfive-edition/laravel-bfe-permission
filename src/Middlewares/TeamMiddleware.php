<?php

namespace BigFiveEdition\Permission\Middlewares;

use BigFiveEdition\Permission\Exceptions\UnauthorizedException;
use Closure;
use Illuminate\Support\Facades\Auth;

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
		} else if (stripos($team, '&')) {
			$teams = is_array($team) ? $team : explode('&', $team);
			if (!$user->belongsToAllTeams($teams)) {
				throw UnauthorizedException::forTeams($teams);
			}
		} else {
			$teams = is_array($team) ? $team : [$team];
			if (!$user->belongsToAnyTeams($teams)) {
				throw UnauthorizedException::forTeams($teams);
			}
		}

		return $next($request);
	}
}
