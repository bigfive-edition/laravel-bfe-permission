<?php

namespace BigFiveEdition\Permission\Traits;

use BigFiveEdition\Permission\Models\Ability;
use BigFiveEdition\Permission\Models\AbilityModel;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\Relations\MorphToMany;
use Illuminate\Support\Collection;

trait HasBfePermissionAbilities
{
	public function addAbilitiesOn(array $abilities, $resourceType = null, $resourceId = null): Collection|array|null
	{
		$abilityIds = Ability::query()
			->whereIn('slug', $abilities)
			->pluck('id')->all();

		$items = collect($abilityIds)->map(function ($abilityId) use ($resourceType, $resourceId) {
			return AbilityModel::firstOrCreate(
				[
					'ability_id' => $abilityId,
					'model_id' => $this->id,
					'model_type' => $this->getMorphClass(),
					'resource_type' => $resourceType,
					'resource_id' => $resourceId,
				]);
		});

		$this->ability_models()->saveMany($items);
		$this->refresh();

		return $this->abilities;
	}

	public function ability_models(): MorphMany
	{
		return $this->morphMany(AbilityModel::class, 'model');
	}

	public function removeAbilitiesOn(array $abilities, $resourceType = null, $resourceId = null): Collection|array|null
	{
		$abilityIds = Ability::query()
			->whereIn('slug', $abilities)
			->pluck('id')->all();

		if (isset($resourceType)) { //TODO: implement this
		}
		if (isset($resourceId)) { //TODO: implement this
		}

		$this->abilities()->detach($abilityIds);
		$this->refresh();

		return $this->abilities;
	}

	public function abilities(): MorphToMany
	{
		return $this->morphToMany(Ability::class, 'model', 'bfe_permission_model_has_abilities_on_resource', 'model_id', 'ability_id');
	}

	public function syncAbilitiesOn(array $abilities, $resourceType = null, $resourceId = null): Collection|array|null
	{
		$abilityIds = Ability::query()
			->whereIn('slug', $abilities)
			->pluck('id')->all();

		if (isset($resourceType)) { //TODO: implement this
		}
		if (isset($resourceId)) { //TODO: implement this
		}

		$this->abilities()->sync($abilityIds);
		$this->refresh();

		return $this->abilities;
	}

	public function hasAnyAbilitiesOn(array $abilities, $resourceType = null, $resourceId = null): bool
	{
		$count = $this->ability_models();
		$count = $count->where('allowed', true);
		if (isset($resourceType)) {
			$count = $count->where('resource_type', $resourceType);
		}
		if (isset($resourceId)) {
			$count = $count->where('resource_id', $resourceId);
		}
		$count = $count->whereHas('ability', function ($query) use ($abilities) {
			$query->whereIn('slug', $abilities);
		})->count();

		return $count > 0;
	}

	public function hasAllAbilitiesOn(array $abilities, $resourceType = null, $resourceId = null): bool
	{
		$count = $this->ability_models();
		$count = $count->where('allowed', true);
		if (isset($resourceType)) {
			$count = $count->where('resource_type', $resourceType);
		}
		if (isset($resourceId)) {
			$count = $count->where('resource_id', $resourceId);
		}
		$count = $count->whereHas('ability', function ($query) use ($abilities) {
			$query->whereIn('slug', $abilities);
		})->count();

		return $count >= count($abilities);
	}
}
