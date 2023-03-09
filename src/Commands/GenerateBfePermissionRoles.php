<?php

declare(strict_types=1);

namespace BigFiveEdition\Permission\Commands;

use BigFiveEdition\Permission\Models\Role;
use Exception;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class GenerateBfePermissionRoles extends Command
{
	protected $signature = 'bfe-permission:generate-roles';
	protected $description = 'Generate Bfe Permission Roles';

	public function __construct()
	{
		parent::__construct();
	}

	public function handle()
	{
		$this->defaultRoles();
	}

	private function defaultRoles()
	{
		try {
			$roles = config('bfe-permission.default_roles', []);
			foreach ($roles as $role) {
				try {
					$key = $role;
					$role = Role::findOrCreate($key, $key);
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
