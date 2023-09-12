<?php

namespace BigFiveEdition\Permission\Models;

use Astrotomic\Translatable\Translatable;
use BigFiveEdition\Permission\Contracts\AbilityContract;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Ability extends Model implements AbilityContract
{
	use Translatable;

	public $incrementing = true;
	public $timestamps = true;
	protected $table = 'bfe_permission_abilities';
	protected $fillable = [
		'slug',
		'name',
		'resource'
	];
	protected $guarded = [
	];
	public $translatedAttributes = ['name'];

	public function __construct(array $attributes = [])
	{
		parent::__construct($attributes);
	}

	public static function findBySlug(string $slug): ?AbilityContract
	{
		$ability = static::findByParam(['slug' => $slug]);
		if (!$ability) {
//			throw BfeAbilityDoesNotExist::withSlug($slug);
		}
		return $ability;
	}

	protected static function findByParam(array $params = [])
	{
		$query = static::query();
		foreach ($params as $key => $value) {
			$query->where($key, $value);
		}
		return $query->first();
	}

	public static function findByName(string $name): ?AbilityContract
	{
		$ability = static::findByParam(['name' => $name]);
		if (!$ability) {
//			throw BfeAbilityDoesNotExist::create($name);
		}
		return $ability;
	}

	public static function findById(int $id): ?AbilityContract
	{
		$ability = static::findByParam(['id' => $id]);
		if (!$ability) {
//			throw BfeAbilityDoesNotExist::withId($id);
		}
		return $ability;
	}

	public static function findOrCreate(string $name, string $slug, ?string $resource): ?AbilityContract
	{
		$ability = static::findBySlug($slug);
		if (!$ability) {
			$created = static::query()->create(
				[
					'name' => $name,
					'slug' => $slug,
					'resource' => $resource,
				]);
			$created?->fill([
				'translations' => [
					'en' => [
						'name' => $name,
					],
					'fr' => [
						'name' => $name,
					],
				]
			]);
			$created?->save();
			return $created;
		}
		return $ability;
	}

	public function ability_models(): HasMany
	{
		return $this->hasMany(AbilityModel::class, 'ability_id', 'id');
	}
}
