<?php

namespace BigFiveEdition\Permission\Models;

use BigFiveEdition\Permission\Contracts\RoleModelContract;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class RoleModel extends Model implements RoleModelContract
{
	public $incrementing = true;
	public $timestamps = true;
	protected $table = 'bfe_permission_model_has_roles';
	protected $fillable = [
	];
	protected $guarded = [
	];

	public function __construct(array $attributes = [])
	{
		parent::__construct($attributes);
	}

	public function role(): BelongsTo
	{
		return $this->belongsTo(Role::class, 'role_id', 'id');
	}

	public function model(): MorphTo
	{
		return $this->morphTo(null, 'model_type', 'model_id', 'id');
	}
}
