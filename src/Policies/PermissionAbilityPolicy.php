<?php

namespace BigFiveEdition\Permission\Policies;

use Illuminate\Auth\Access\HandlesAuthorization;
use Illuminate\Support\Arr;

class PermissionAbilityPolicy
{
	use HandlesAuthorization;

	public function hasAbility($model, $ability, $resource = null): bool
	{
		$type = $resource ? get_class($resource) : null;
		$id = $resource ? Arr::get($resource, 'id') : null;

		if (stripos($ability, '|')) {
			$abilities = is_array($ability) ? $ability : explode('|', $ability);
			return $model->hasAnyAbilitiesOn($abilities, $type, $id);
		} else if (stripos($ability, '&')) {
			$abilities = is_array($ability) ? $ability : explode('&', $ability);
			return $model->hasAllAbilitiesOn($abilities, $type, $id);
		} else {
			$abilities = is_array($ability) ? $ability : [$ability];
			return $model->hasAnyAbilitiesOn($abilities, $type, $id);
		}

		return false;
	}
}
