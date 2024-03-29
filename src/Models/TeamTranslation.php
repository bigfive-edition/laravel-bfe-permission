<?php

namespace BigFiveEdition\Permission\Models;

use Illuminate\Database\Eloquent\Model;

class TeamTranslation extends Model
{
	public $incrementing = true;
	public $timestamps = false;
	protected $table = 'bfe_permission_teams_translations';
	protected $fillable = [
		'team_id',
		'locale',
		'name',
	];
	protected $guarded = [
	];

	public function __construct(array $attributes = [])
	{
		parent::__construct($attributes);
	}
}
