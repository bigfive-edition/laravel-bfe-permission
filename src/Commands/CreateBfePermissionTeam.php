<?php

declare(strict_types=1);

namespace BigFiveEdition\Permission\Commands;

use BigFiveEdition\Permission\Models\Team;
use Illuminate\Console\Command;

class CreateBfePermissionTeam extends Command
{
	protected $signature = 'bfe-permission:create-team
        {slug : The slug of the team, must be unique}
        {name : The name of the team}';
	protected $description = 'Create BfePermission Team';

	public function __construct()
	{
		parent::__construct();
	}

	public function handle()
	{
		$team = Team::findOrCreate($this->argument('name'), $this->argument('slug'));

		$this->info("Team `{$team->name}` ".($team->wasRecentlyCreated ? 'created' : 'updated'));
	}
}
