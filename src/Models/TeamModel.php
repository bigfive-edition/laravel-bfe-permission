<?php

namespace BigFiveEdition\Permission\Models;

use BigFiveEdition\Permission\Contracts\TeamModel as TeamModelContract;
use Illuminate\Database\Eloquent\Model;

class TeamModel extends Model implements TeamModelContract
{
	public $incrementing = true;
	public $timestamps = true;
	protected $table = 'bfe_permission_model_belongs_teams';
	protected $fillable = [
	];
	protected $guarded = [
	];

	public function __construct(array $attributes = [])
	{
		parent::__construct($attributes);
	}
}
