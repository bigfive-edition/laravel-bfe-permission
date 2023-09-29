<?php

namespace BigFiveEdition\Permission\Models;

use Astrotomic\Translatable\Translatable;
use BigFiveEdition\Permission\Contracts\RoleContract;
use BigFiveEdition\Permission\Exceptions\BfeRoleDoesNotExist;
use BigFiveEdition\Permission\Traits\HasBfePermissionAbilities;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Role extends Model implements RoleContract
{
	use HasBfePermissionAbilities;
	use Translatable;

	public $incrementing = true;
	public $timestamps = true;
	protected $table = 'bfe_permission_roles';
	protected $fillable = [
		'slug',
		'name',
	];
	protected $guarded = [
	];
	public $translatedAttributes = ['name'];

	public function __construct(array $attributes = [])
	{
		parent::__construct($attributes);
	}

	public static function findBySlug(string $slug): ?RoleContract
	{
		$role = static::findByParam(['slug' => $slug]);
		if (!$role) {
//			throw BfeRoleDoesNotExist::withSlug($slug);
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

	public static function findByName(string $name): ?RoleContract
	{
		$role = static::findByParam(['name' => $name]);
		if (!$role) {
//			throw BfeRoleDoesNotExist::create($name);
		}
		return $role;
	}

	public static function findById(int $id): ?RoleContract
	{
		$role = static::findByParam(['id' => $id]);
		if (!$role) {
//			throw BfeRoleDoesNotExist::withId($id);
		}
		return $role;
	}

	public static function findOrCreate(string $name, string $slug): ?RoleContract
	{
		$role = static::findBySlug($slug);
		if (!$role) {
			$created = static::query()->create(
				[
					'name' => $name,
					'slug' => $slug,
				]);
			$created?->fill([
				'en' => [
					'name' => $name,
				],
				'fr' => [
					'name' => $name,
				],
			]);
			$created?->save();
			return $created;
		}
		return $role;
	}

	public function role_models(): HasMany
	{
		return $this->hasMany(RoleModel::class, 'role_id', 'id');
	}
}
