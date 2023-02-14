<?php

namespace BigFiveEdition\Permission\Models;

use BigFiveEdition\Permission\Contracts\RoleContract;
use BigFiveEdition\Permission\Exceptions\RoleDoesNotExist;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Role extends Model implements RoleContract
{
	public $incrementing = true;
	public $timestamps = true;
	protected $table = 'bfe_permission_roles';
	protected $fillable = [
	];
	protected $guarded = [
	];

	public function __construct(array $attributes = [])
	{
		parent::__construct($attributes);
	}

	public function role_models(): HasMany
	{
		return $this->hasMany(RoleModel::class, 'role_id', 'id');
	}

	public static function findBySlug(string $slug): RoleContract
	{
		$role = static::findByParam(['slug' => $slug]);
		if (!$role) {
			throw RoleDoesNotExist::withSlug($slug);
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
}
