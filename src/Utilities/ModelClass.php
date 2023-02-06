<?php

namespace BigFiveEdition\Permission\Utilities;

use Cache;
use File;
use Exception;
use Illuminate\Container\Container;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use ReflectionClass;

class ModelClass
{
	public static function all(): Collection
	{
		try {
			$models = collect(File::allFiles(app_path()))
				->map(function ($item) {
					$path = $item->getRelativePathName();
					$class = sprintf('\%s%s',
						Container::getInstance()->getNamespace(),
						strtr(substr($path, 0, strrpos($path, '.')), '/', '\\'));

					return $class;
				})
				->filter(function ($class) {
					$valid = false;
//					if (Str::contains($class, '\\Models\\')) {
						try {
							$reflection = new ReflectionClass($class);
							$valid = $reflection->isSubclassOf(Model::class) &&
								!$reflection->isAbstract();
						} catch (Exception $e) {
							Log::error($e->getMessage());
							Log::error($e->getTraceAsString());
						}
//					}
					return $valid;
				});

			return $models->values();
		} catch (Exception $e) {
			Log::error($e->getMessage());
			Log::error($e->getTraceAsString());
		}
		return new Collection([]);
	}
}
