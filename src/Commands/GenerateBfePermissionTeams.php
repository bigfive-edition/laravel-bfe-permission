<?php

declare(strict_types=1);

namespace BigFiveEdition\Permission\Commands;

use BigFiveEdition\Permission\Models\Team;
use Exception;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class GenerateBfePermissionTeams extends Command
{
	protected $signature = 'bfe-permission:generate-teams';
	protected $description = 'Generate Bfe Permission Teams';

	public function __construct()
	{
		parent::__construct();
	}

	public function handle()
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
					$team = Team::findOrCreate($key, $key);
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
