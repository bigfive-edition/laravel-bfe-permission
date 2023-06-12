<?php

namespace BigFiveEdition\Permission\Middlewares;

use BigFiveEdition\Permission\Exceptions\BfeUnauthorizedException;
use BigFiveEdition\Permission\Traits\HasBfePermissionRoles;
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
			throw BfeUnauthorizedException::notLoggedIn();
		}
		//$user = $authGuard->user();
		$user = $request->user();

		$isAuthorized = false;
		$isAndOperation = false;
		$roles = [];

		//get roles
		if (stripos($role, '|')) {
			$roles = is_array($role) ? $role : array_map('trim', explode('|', $role));
		} else if (stripos($role, '&')) {
			$isAndOperation = true;
			$roles = is_array($role) ? $role : array_map('trim', explode('&', $role));
		} else {
			$roles = is_array($role) ? $role : [$role];
		}

		$models = [$user];

		//loop through models with abilities
		foreach ($models as $model) {
			try {
				if (in_array(HasBfePermissionRoles::class, class_uses_recursive(get_class($model)), true)) {
					if ($isAndOperation) {
						$isAuthorized = $model->hasAllRoles($roles);
					} else {
						$isAuthorized = $model->hasAnyRoles($roles);
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
			throw BfeUnauthorizedException::forRoles($roles);
		}

		return $next($request);
	}
}
