<?php

namespace BigFiveEdition\Permission\Middlewares;

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

		$teams = is_array($team)
			? $team
			: explode('|', $team);

		if (!$authGuard->user()->hasAnyTeams($teams)) {
			throw UnauthorizedException::forTeams($teams);
		}

		return $next($request);
	}
}
