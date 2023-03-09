<?php

namespace BigFiveEdition\Permission\Models;

use BigFiveEdition\Permission\Contracts\AbilityModelContract;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class AbilityModel extends Model implements AbilityModelContract
{
	public $incrementing = true;
	public $timestamps = true;
	protected $table = 'bfe_permission_model_has_abilities_on_resource';
	protected $fillable = [
	];
	protected $guarded = [
	];

	public function __construct(array $attributes = [])
	{
		parent::__construct($attributes);
	}

	public function ability(): BelongsTo
	{
		return $this->belongsTo(Ability::class, 'ability_id', 'id');
	}

	public function model(): MorphTo
	{
		return $this->morphTo(null, 'model_type', 'model_id', 'id');
	}

	public function resource(): MorphTo
	{
		return $this->morphTo(null, 'resource_type', 'resource_id', 'id');
	}
}
