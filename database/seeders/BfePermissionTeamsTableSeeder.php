<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Log;

class BfePermissionTeamsTableSeeder extends Seeder
{
	/**
	 * Run the database seeds.
	 *
	 * @return void
	 */
	public function run()
	{
		$this->defaultTeams();
	}


	private function defaultTeams()
	{
		try {
			$teams = config('bfe-permission.default_teams', []);
			foreach ($teams as $team) {
				try {
					$key = $team;
					$team = \BigFiveEdition\Permission\Models\Team::findOrCreate($key, $key);
				} catch (Exception $e) {
					Log::error($e->getMessage());
					Log::error($e->getTraceAsString());
				}
			}
		} catch (Exception $e) {
			Log::error($e->getMessage());
			Log::error($e->getTraceAsString());
		}
	}
}
