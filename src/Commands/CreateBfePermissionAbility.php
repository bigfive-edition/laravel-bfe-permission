<?php

declare(strict_types=1);

namespace BigFiveEdition\Permission\Commands;

use BigFiveEdition\Permission\Models\Ability;
use Illuminate\Console\Command;

class CreateBfePermissionAbility extends Command
{
	protected $signature = 'bfe-permission:create-ability
        {slug : The slug of the ability, must be unique}
        {name : The name of the ability}
        {resource : The resource of the ability}';
	protected $description = 'Create BfePermission Ability';

	public function __construct()
	{
		parent::__construct();
	}

	public function handle()
	{
		$ability = Ability::findOrCreate($this->argument('name'), $this->argument('slug'), $this->argument('resource'));

		$this->info("Ability `{$ability->name}` ".($ability->wasRecentlyCreated ? 'created' : 'updated'));
	}
}
