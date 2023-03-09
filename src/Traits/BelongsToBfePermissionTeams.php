<?php

namespace BigFiveEdition\Permission\Traits;

use BigFiveEdition\Permission\Models\Team;
use BigFiveEdition\Permission\Models\TeamModel;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\Relations\MorphToMany;
use Illuminate\Support\Collection;

trait BelongsToBfePermissionTeams
{
	public function team_models(): MorphMany
	{
		return $this->morphMany(TeamModel::class, 'model');
	}

	public function teams(): MorphToMany
	{
		return $this->morphToMany(Team::class, 'model', 'bfe_permission_model_belongs_teams', 'model_id', 'team_id');
	}

	public function addToTeams(...$teams): Collection|array|null
	{
		return null;
	}

	public function removeFromTeams(...$teams): Collection|array|null
	{
		return null;
	}

	public function syncTeams(...$teams): Collection|array|null
	{
		return null;
	}

	public function belongsToAnyTeams(...$teams): bool
	{
		return false;
	}

	public function belongsToAllTeams(...$teams): bool
	{
		return false;
	}
}
