<?php

namespace BigFiveEdition\Permission\Models;

use BigFiveEdition\Permission\Contracts\Role as RoleContract;
use BigFiveEdition\Permission\Exceptions\RoleDoesNotExist;
use Illuminate\Database\Eloquent\Model;

class Role extends Model implements RoleContract
{
	public $incrementing = true;
	public $timestamps = true;
	protected $table = 'bfe_roles';
	protected $fillable = [
	];
	protected $guarded = [
	];

	public function __construct(array $attributes = [])
	{
		parent::__construct($attributes);
	}

	public static function findBySlug(string $slug): RoleContract
	{
		$role = static::findByParam(['slug' => $slug]);
		if (!$role) {
			throw RoleDoesNotExist::withSlug($slug);
		}
		return $role;
	}

	public static function findByName(string $name): RoleContract
	{
		$role = static::findByParam(['name' => $name]);
		if (!$role) {
			throw RoleDoesNotExist::create($name);
		}
		return $role;
	}

	public static function findById(int $id): RoleContract
	{
		$role = static::findByParam(['id' => $id]);
		if (!$role) {
			throw RoleDoesNotExist::withId($id);
		}
		return $role;
	}

	public static function findOrCreate(string $name, string $slug): RoleContract
	{
		$role = static::findByParam(['name' => $name, 'slug' => $slug]);
		if (!$role) {
			return static::query()->create(['name' => $name, 'slug' => $slug]);
		}
		return $role;
	}

	protected static function findByParam(array $params = [])
	{
		$query = static::query();
		foreach ($params as $key => $value) {
			$query->where($key, $value);
		}
		return $query->first();
	}
}
