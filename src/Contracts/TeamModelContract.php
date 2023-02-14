<?php

namespace BigFiveEdition\Permission\Contracts;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;

interface TeamModelContract
{
	public function team(): BelongsTo;
	public function model(): MorphTo;
}
