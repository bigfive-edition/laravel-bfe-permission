<?php

use BigFiveEdition\Permission\Models\Role;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Log;

class BfePermissionRolesTableSeeder extends Seeder
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
