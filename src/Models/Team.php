<?php

namespace BigFiveEdition\Permission\Models;

use Astrotomic\Translatable\Translatable;
use BigFiveEdition\Permission\Contracts\TeamContract;
use BigFiveEdition\Permission\Traits\HasBfePermissionAbilities;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Team extends Model implements TeamContract
{
	use HasBfePermissionAbilities;
	use Translatable;
	public $incrementing = true;
	public $timestamps = true;
	protected $table = 'bfe_permission_teams';
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

	public static function findBySlug(string $slug): ?TeamContract
	{
		$team = static::findByParam(['slug' => $slug]);
		if (!$team) {
//			throw BfeTeamDoesNotExist::withSlug($slug);
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

	public static function findByName(string $name): ?TeamContract
	{
		$team = static::findByParam(['name' => $name]);
		if (!$team) {
//			throw BfeTeamDoesNotExist::create($name);
		}
		return $team;
	}

	public static function findById(int $id): ?TeamContract
	{
		$team = static::findByParam(['id' => $id]);
		if (!$team) {
//			throw BfeTeamDoesNotExist::withId($id);
		}
		return $team;
	}

	public static function findOrCreate(string $name, string $slug): ?TeamContract
	{
		$team = static::findBySlug($slug);
		if (!$team) {
			$created = static::query()->create(
				[
					'name' => $name,
					'slug' => $slug,
				]);
			$created?->fill([
				'translations' => [
					'en' => [
						'name' => $name,
					],
					'fr' => [
						'name' => $name,
					],
				],
			]);
			$created?->save();
		}
		return $team;
	}

	public function team_models(): HasMany
	{
		return $this->hasMany(TeamModel::class, 'team_id', 'id');
	}
}
