<?php

namespace BigFiveEdition\Permission\Models;

use BigFiveEdition\Permission\Contracts\TeamModelContract;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class TeamModel extends Model implements TeamModelContract
{
	public $incrementing = true;
	public $timestamps = true;
	protected $table = 'bfe_permission_model_belongs_teams';
	protected $fillable = [
		'team_id',
		'model_type',
		'model_id',
		'attribute',
	];
	protected $guarded = [
	];

	public function __construct(array $attributes = [])
	{
		parent::__construct($attributes);
	}

	public function team(): BelongsTo
	{
		return $this->belongsTo(Team::class, 'team_id', 'id');
	}

	public function model(): MorphTo
	{
		return $this->morphTo(null, 'model_type', 'model_id', 'id');
	}
}
