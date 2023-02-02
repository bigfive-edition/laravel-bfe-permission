<?php

namespace BigFiveEdition\Permission\Models;

use BigFiveEdition\Permission\Contracts\Ability as AbilityContract;
use BigFiveEdition\Permission\Exceptions\AbilityDoesNotExist;
use Illuminate\Database\Eloquent\Model;

class Ability extends Model implements AbilityContract
{
	public $incrementing = true;
	public $timestamps = true;
	protected $table = 'bfe_abilities';
	protected $fillable = [
	];
	protected $guarded = [
	];

	public function __construct(array $attributes = [])
	{
		parent::__construct($attributes);
	}

	public static function findBySlug(string $slug): AbilityContract
	{
		$ability = static::findByParam(['slug' => $slug]);
		if (!$ability) {
			throw AbilityDoesNotExist::withSlug($slug);
		}
		return $ability;
	}

	public static function findByName(string $name): AbilityContract
	{
		$ability = static::findByParam(['name' => $name]);
		if (!$ability) {
			throw AbilityDoesNotExist::create($name);
		}
		return $ability;
	}

	public static function findById(int $id): AbilityContract
	{
		$ability = static::findByParam(['id' => $id]);
		if (!$ability) {
			throw AbilityDoesNotExist::withId($id);
		}
		return $ability;
	}

	public static function findOrCreate(string $name, string $slug): AbilityContract
	{
		$ability = static::findByParam(['name' => $name, 'slug' => $slug]);
		if (!$ability) {
			return static::query()->create(['name' => $name, 'slug' => $slug]);
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
}
