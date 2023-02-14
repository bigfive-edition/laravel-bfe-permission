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
		return null;
	}

	public function removeAbilitiesOn($abilities, $resources): Collection|array|null
	{
		return null;
	}

	public function syncAbilitiesOn($abilities, $resources): Collection|array|null
	{
		return null;
	}

	public function hasAnyAbilitiesOn($abilities, $resources): bool
	{
		return false;
	}

	public function hasAllAbilitiesOn($abilities, $resources): bool
	{
		return false;
	}
}
