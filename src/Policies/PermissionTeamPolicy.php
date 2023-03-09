<?php

namespace BigFiveEdition\Permission\Policies;

use Illuminate\Auth\Access\HandlesAuthorization;

class PermissionTeamPolicy
{
	use HandlesAuthorization;

	public function belongsToTeam($model, $team): bool
	{
		if (stripos($team, '|')) {
			$teams = is_array($team) ? $team : explode('|', $team);
			return $model->belongsToAnyTeams($teams);
		}

		if (stripos($team, '&')) {
			$teams = is_array($team) ? $team : explode('&', $team);
			return $model->belongsToAllTeams($teams);
		}
		return false;
	}
}
