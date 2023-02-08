<?php

declare(strict_types=1);

namespace BigFiveEdition\Permission\Commands;

use BigFiveEdition\Permission\Models\Role;
use Illuminate\Console\Command;

class CreateBfePermissionRole extends Command
{
	protected $signature = 'bfe-permission:create-role
        {slug : The slug of the role, must be unique}
        {name : The name of the role}';
	protected $description = 'Create BfePermission Role';

	public function __construct()
	{
		parent::__construct();
	}

	public function handle()
	{
		$role = Role::findOrCreate($this->argument('name'), $this->argument('slug'));

		$this->info("Role `{$role->name}` " . ($role->wasRecentlyCreated ? 'created' : 'updated'));
	}
}
