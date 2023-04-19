<?php

namespace BigFiveEdition\Permission\Middlewares;

use BigFiveEdition\Permission\Exceptions\BfeUnauthorizedException;
use BigFiveEdition\Permission\Traits\BelongsToBfePermissionTeams;
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
			throw BfeUnauthorizedException::notLoggedIn();
		}

		//$user = $authGuard->user();
		$user = $request->user();

		$isAuthorized = false;
		$isAndOperation = false;
		$teams = [];

		//get teams
		if (stripos($team, '|')) {
			$teams = is_array($team) ? $team : explode('|', $team);
		} else if (stripos($team, '&')) {
			$isAndOperation = true;
			$teams = is_array($team) ? $team : explode('&', $team);
		} else {
			$teams = is_array($team) ? $team : [$team];
		}

		$models = [$user];

		//loop through models with abilities
		foreach ($models as $model) {
			try {
				if (in_array(BelongsToBfePermissionTeams::class, class_uses_recursive(get_class($model)), true)) {
					if($isAndOperation) {
						$isAuthorized = $model->belongsToAllTeams($teams);
					}else {
						$isAuthorized = $model->belongsToAnyTeams($teams);
					}
				}
			} catch (Exception $e) {
				Log::error($e->getMessage());
				Log::error($e->getTraceAsString());
			}

			//if is authorized stop checking
			if ($isAuthorized) {
				break;
			}
		}

		if (!$isAuthorized) {
			throw BfeUnauthorizedException::forTeams($teams);
		}

		return $next($request);
	}
}
