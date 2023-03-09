<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Log;

class BfePermissionDatabaseSeeder extends Seeder
{
	/**
	 * Seed the application's database.
	 *
	 * @return void
	 */
	public function run()
	{
		Log::info("------- START: " . get_class($this) . " -------");
		$seeders = [
			BfePermissionTeamsTableSeeder::class,
			BfePermissionRolesTableSeeder::class,
			BfePermissionAbilitiesTableSeeder::class,
		];

		if (in_array(env('APP_ENV'), ['local', 'testing'], true)) {
			$seeders = array_merge($seeders, [
			]);

			if (env('APP_ENV') === 'testing') {
				$seeders = array_merge($seeders, [
				]);
			}
		}

		$this->call($seeders);
		Log::info("------- DONE: " . get_class($this) . " -------");
	}
}
