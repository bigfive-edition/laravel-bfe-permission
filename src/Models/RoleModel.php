<?php

namespace BigFiveEdition\Permission\Models;

use BigFiveEdition\Permission\Contracts\RoleModel as RoleModelContract;
use Illuminate\Database\Eloquent\Model;

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
}
