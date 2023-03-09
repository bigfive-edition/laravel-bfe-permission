<?php

namespace BigFiveEdition\Permission\Policies;

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
		$allowed = false;
		try {
			if (stripos($team, '|')) {
				$teams = is_array($team) ? $team : explode('|', $team);
				$allowed = $user->belongsToAnyTeams($teams);
			} else if (stripos($team, '&')) {
				$teams = is_array($team) ? $team : explode('&', $team);
				$allowed = $user->belongsToAllTeams($teams);
			} else {
				$teams = is_array($team) ? $team : [$team];
				$allowed = $user->belongsToAnyTeams($teams);
			}
		} catch (Exception $e) {
			Log::error($e->getMessage());
			Log::error($e->getTraceAsString());
		}
//		return $allowed;
		return $allowed ? Response::allow() : Response::deny('You do not belong to the required teams to perform operation.');
	}
}
