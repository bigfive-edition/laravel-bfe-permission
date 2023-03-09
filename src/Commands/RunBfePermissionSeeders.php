<?php

declare(strict_types=1);

namespace BigFiveEdition\Permission\Commands;

use Illuminate\Console\Command;

class RunBfePermissionSeeders extends Command
{
	protected $signature = 'bfe-permission:seed';
	protected $description = 'Run Bfe Permission Seeders';

	public function __construct()
	{
		parent::__construct();
	}

	public function handle()
	{
		$this->call("db:seed", [
			'--class' => 'BfePermissionDatabaseSeeder',
		]);
	}
}
