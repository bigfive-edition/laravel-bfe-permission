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
		try {
			if (stripos($team, '|')) {
				$teams = is_array($team) ? $team : explode('|', $team);
				return $user->belongsToAnyTeams($teams);
			} else if (stripos($team, '&')) {
				$teams = is_array($team) ? $team : explode('&', $team);
				return $user->belongsToAllTeams($teams);
			} else {
				$teams = is_array($team) ? $team : [$team];
				return $user->belongsToAnyTeams($teams);
			}
		} catch (Exception $e) {
			Log::error($e->getMessage());
			Log::error($e->getTraceAsString());
		}
		return false;
	}
}
