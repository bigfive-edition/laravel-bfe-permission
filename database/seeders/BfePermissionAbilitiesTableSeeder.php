<?php

use BigFiveEdition\Permission\Contracts\AbilityOperationType;
use BigFiveEdition\Permission\Models\Ability;
use BigFiveEdition\Permission\Models\AbilityModel;
use BigFiveEdition\Permission\Models\Role;
use BigFiveEdition\Permission\Models\RoleModel;
use BigFiveEdition\Permission\Models\Team;
use BigFiveEdition\Permission\Models\TeamModel;
use BigFiveEdition\Permission\Utilities\ModelClass;
use Illuminate\Database\Seeder;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Log;

class BfePermissionAbilitiesTableSeeder extends Seeder
{
	/**
	 * Run the database seeds.
	 *
	 * @return void
	 */
	public function run()
	{
		$this->defaultAbilities();
	}


	private function defaultAbilities()
	{
		try {

			$abilitiesData = $this->getAllAbilitiesData();

			$existingAbilitiesSlugs = Ability::pluck('slug')->all();
			$filteredAbilitiesData = Arr::where($abilitiesData, function ($value, $slug) use ($existingAbilitiesSlugs) {
				$slug = $value['slug'];
				return !in_array($slug, $existingAbilitiesSlugs);
			});

			$this->saveAbilitiesData($abilitiesData);
		} catch (Exception $e) {
			Log::error($e->getMessage());
			Log::error($e->getTraceAsString());
		}
	}

	private function getAllAbilitiesData()
	{
		try {

			$abilitiesData = [];
			$resources = [
				Team::class,
				TeamModel::class,
				Role::class,
				RoleModel::class,
				Ability::class,
				AbilityModel::class,
			];
			$configResources = config('bfe-permission.ability_resources', []);
			if ($configResources && is_array($configResources)) {
				$resources = array_merge($resources, $configResources);
			}
			foreach ($resources as $model) {
				$operations = config('bfe-permission.ability_operations', AbilityOperationType::ALL);
				foreach ($operations as $operation) {
					try {
						$reflect = new ReflectionClass($model);
						$modelSlug = strtolower($reflect->getShortName());
						$slug = "{$operation}_{$modelSlug}";
						$abilitiesData[] = [
							'slug' => $slug,
							'name' => ucwords($operation) . ' ' . $reflect->getShortName(),
							'resource' => $model,
						];
					} catch (Exception $e) {
						Log::error($e->getMessage());
						Log::error($e->getTraceAsString());
					}
				}
			}

			return $abilitiesData;
		} catch (Exception $e) {
			Log::error($e->getMessage());
			Log::error($e->getTraceAsString());
		}
		return [];
	}

	private function saveAbilitiesData($abilitiesData)
	{
		if (count($abilitiesData) <= 0) {
			return;
		}
		foreach ($abilitiesData as $data) {
			try {
				$slug = Arr::get($data, 'slug');
				$name = Arr::get($data, 'name');
				$resource = Arr::get($data, 'resource');
				$role = Ability::findOrCreate($name, $slug, $resource);
			} catch (Exception $e) {
				Log::error($e->getMessage());
				Log::error($e->getTraceAsString());
			}
		}
	}
}
