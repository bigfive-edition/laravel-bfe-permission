<?php

use BigFiveEdition\Permission\Contracts\AbilityOperationType;
use BigFiveEdition\Permission\Models\Ability;
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
		$this->defaultRoles();
	}


	private function defaultRoles()
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
			$models = ModelClass::all();
			foreach ($models as $model) {
				$operations = AbilityOperationType::ALL;
				foreach ($operations as $operation) {
					try {
						$reflect = new ReflectionClass($model);
						$modelSlug = strtolower($reflect->getShortName());
						$slug = "{$operation}_{$modelSlug}";
						$abilitiesData[] = [
							'slug' => $slug,
							'name' => ucwords($operation) . ' ' . ucwords($modelSlug),
							'resource_type' => $model,
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
				$role = Ability::findOrCreate($slug, $name);
			} catch (Exception $e) {
				Log::error($e->getMessage());
				Log::error($e->getTraceAsString());
			}
		}
	}
}
