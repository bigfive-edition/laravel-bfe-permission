<?php

namespace BigFiveEdition\Permission\Contracts;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;

interface RoleModelContract
{
	public function role(): BelongsTo;

	public function model(): MorphTo;
}
