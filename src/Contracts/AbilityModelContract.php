<?php

namespace BigFiveEdition\Permission\Contracts;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;

interface AbilityModelContract
{
	public function ability(): BelongsTo;
	public function model(): MorphTo;
	public function resource(): MorphTo;
}
