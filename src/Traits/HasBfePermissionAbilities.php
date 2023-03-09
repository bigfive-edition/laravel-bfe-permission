<?php

namespace BigFiveEdition\Permission\Traits;

use BigFiveEdition\Permission\Models\Ability;
use BigFiveEdition\Permission\Models\AbilityModel;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\Relations\MorphToMany;
use Illuminate\Support\Collection;

trait HasBfePermissionAbilities
{
	public function ability_models(): MorphMany
	{
		return $this->morphMany(AbilityModel::class, 'model');
	}

	public function abilities(): MorphToMany
	{
		return $this->morphToMany(Ability::class, 'model', 'bfe_permission_model_has_abilities_on_resource', 'model_id', 'ability_id');
	}

	public function addAbilitiesOn($abilities, $resources): Collection|array|null
	{
		$abilityIds = Ability::query()
			->whereIn('slug', $abilities)
			->pluck('id')->all();

		$this->ability_models()->saveMany(collect($abilityIds)->map(function ($abilityId) {
			return AbilityModel::firstOrCreate(
				[
					'ability_id' => $abilityId,
					'model_id' => $this->id,
					'model_type' => $this->getMorphClass(),
				],
				[
					'attribute' => 'member',
				]);
		}));
		$this->refresh();

		return $this->abilities;
	}

	public function removeAbilitiesOn($abilities, $resources): Collection|array|null
	{
		$abilityIds = Ability::query()
			->whereIn('slug', $abilities)
			->pluck('id')->all();

		$this->abilities()->detach($abilityIds);
		$this->refresh();

		return $this->abilities;
	}

	public function syncAbilitiesOn($abilities, $resources): Collection|array|null
	{
		$abilityIds = Ability::query()
			->whereIn('slug', $abilities)
			->pluck('id')->all();

		$this->abilities()->sync($abilityIds);
		$this->refresh();

		return $this->abilities;
	}

	public function hasAnyAbilitiesOn($abilities, $resources): bool
	{
		return $this->abilities()
				->whereIn('slug', $abilities)
				->count() > 0;
	}

	public function hasAllAbilitiesOn($abilities, $resources): bool
	{
		$count = $this->abilities()
			->whereIn('slug', $abilities)
			->count();

		return $count >= count($abilities);
	}
}
