<?php

declare(strict_types=1);

namespace BigFiveEdition\Permission\Commands;

use App\_PointOfSale\Services\BizaoService;
use Carbon\Carbon;
use Exception;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

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
			'--provider' => 'Spatie\Permission\PermissionServiceProvider',
		]);
		$this->call("vendor:publish", [
			'--provider' => 'BigFiveEdition\Permission\Providers\BfePermissionServiceProvider',
		]);
	}
}
