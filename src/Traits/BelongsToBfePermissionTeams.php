<?php

namespace BigFiveEdition\Permission\Traits;

use BigFiveEdition\Permission\Models\Team;
use BigFiveEdition\Permission\Models\TeamModel;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\Relations\MorphToMany;
use Illuminate\Support\Collection;

trait BelongsToBfePermissionTeams
{
	public function addToTeams($teams): Collection|array|null
	{
		$teamIds = Team::query()
			->whereIn('slug', $teams)
			->pluck('id')->all();

		$items = collect($teamIds)->map(function ($teamId) {
			return TeamModel::firstOrCreate(
				[
					'team_id' => $teamId,
					'model_id' => $this->id,
					'model_type' => $this->getMorphClass(),
				],
				[
					'attribute' => 'member',
				]);
		});
		$this->team_models()->saveMany($items);
		$this->refresh();

		return $this->teams;
	}

	public function team_models(): MorphMany
	{
		return $this->morphMany(TeamModel::class, 'model');
	}

	public function removeFromTeams($teams): Collection|array|null
	{
		$teamIds = Team::query()
			->whereIn('slug', $teams)
			->pluck('id')->all();

		$this->teams()->detach($teamIds);
		$this->refresh();

		return $this->teams;
	}

	public function teams(): MorphToMany
	{
		return $this->morphToMany(Team::class, 'model', 'bfe_permission_model_belongs_teams', 'model_id', 'team_id');
	}

	public function syncTeams($teams): Collection|array|null
	{
		$teamIds = Team::query()
			->whereIn('slug', $teams)
			->pluck('id')->all();

		$this->teams()->sync($teamIds);
		$this->refresh();

		return $this->teams;
	}

	public function belongsToAnyTeams($teams): bool
	{
		$count = $this->team_models()
			->whereHas('team', function ($query) use ($teams) {
				$query->whereIn('slug', $teams);
			})
			->count();

		return $count > 0;
	}

	public function belongsToAllTeams($teams): bool
	{
		$count = $this->team_models()
			->whereHas('team', function ($query) use ($teams) {
				$query->whereIn('slug', $teams);
			})
			->count();

		return $count >= count($teams);
	}
}
