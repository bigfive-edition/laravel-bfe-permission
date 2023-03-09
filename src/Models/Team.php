<?php

namespace BigFiveEdition\Permission\Models;

use BigFiveEdition\Permission\Contracts\Team as TeamContract;
use BigFiveEdition\Permission\Exceptions\TeamDoesNotExist;
use Illuminate\Database\Eloquent\Model;

class Team extends Model implements TeamContract
{
	public $incrementing = true;
	public $timestamps = true;
	protected $table = 'bfe_permission_teams';
	protected $fillable = [
	];
	protected $guarded = [
	];

	public function __construct(array $attributes = [])
	{
		parent::__construct($attributes);
	}

	public static function findBySlug(string $slug): TeamContract
	{
		$team = static::findByParam(['slug' => $slug]);
		if (!$team) {
			throw TeamDoesNotExist::withSlug($slug);
		}
		return $team;
	}

	public static function findByName(string $name): TeamContract
	{
		$team = static::findByParam(['name' => $name]);
		if (!$team) {
			throw TeamDoesNotExist::create($name);
		}
		return $team;
	}

	public static function findById(int $id): TeamContract
	{
		$team = static::findByParam(['id' => $id]);
		if (!$team) {
			throw TeamDoesNotExist::withId($id);
		}
		return $team;
	}

	public static function findOrCreate(string $name, string $slug): TeamContract
	{
		$team = static::findByParam(['name' => $name, 'slug' => $slug]);
		if (!$team) {
			return static::query()->create(['name' => $name, 'slug' => $slug]);
		}
		return $team;
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
