<?php

namespace BigFiveEdition\Permission\Models;

use BigFiveEdition\Permission\Contracts\AbilityContract;
use BigFiveEdition\Permission\Exceptions\BfeAbilityDoesNotExist;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class AbilityTranslation extends Model
{
	public $incrementing = true;
	public $timestamps = false;
	protected $table = 'bfe_permission_abilities_translations';
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
