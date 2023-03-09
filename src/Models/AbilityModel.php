<?php

namespace BigFiveEdition\Permission\Models;

use BigFiveEdition\Permission\Contracts\AbilityModel as AbilityModelContract;
use Illuminate\Database\Eloquent\Model;

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
}
