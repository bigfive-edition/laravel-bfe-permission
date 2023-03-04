<?php

declare(strict_types=1);

namespace BigFiveEdition\Permission\Commands;

use Illuminate\Console\Command;

class InstallBfePermission extends Command
{
	protected $signature = 'bfe-permission:install';
	protected $description = 'Install Bfe Permission';

	public function __construct()
	{
		parent::__construct();
	}

	public function handle()
	{
		$this->call("vendor:publish", [
			'--provider' => 'BigFiveEdition\Permission\Providers\BfePermissionPackageServiceProvider',
		]);
	}
}
