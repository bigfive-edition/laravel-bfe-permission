<?php

namespace BigFiveEdition\Permission\Policies;

use BigFiveEdition\Permission\Traits\BelongsToBfePermissionTeams;
use Exception;
use Illuminate\Auth\Access\HandlesAuthorization;
use Illuminate\Auth\Access\Response;
use Illuminate\Support\Facades\Log;

class PermissionTeamPolicy
{
	use HandlesAuthorization;

	protected $abilities;

	public function before($user, $abilities)
	{
		$this->abilities = $abilities;
	}

	/**
	 * Determine whether the model belongs to the passed team
	 *
	 * @param $user
	 * @param $role
	 * @return Response|bool
	 */
	public function belongsToTeam($user, $team): Response|bool
	{
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

//		return $isAuthorized;
		return $isAuthorized ? Response::allow() : Response::deny('You do not belong to the required teams to perform operation.');
	}
}
