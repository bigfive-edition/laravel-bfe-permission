<?php

namespace BigFiveEdition\Permission\Models;

use Illuminate\Database\Eloquent\Model;

class RoleTranslation extends Model
{
	public $incrementing = true;
	public $timestamps = false;
	protected $table = 'bfe_permission_roles_translations';
	protected $fillable = [
		'name'
	];
	protected $guarded = [
	];

	public function __construct(array $attributes = [])
	{
		parent::__construct($attributes);
	}
}
