<?php

namespace BigFiveEdition\Permission\Exceptions;

use InvalidArgumentException;

class BfeAbilityAlreadyExists extends InvalidArgumentException
{
	public static function create(string $name, string $slug = '')
	{
		return new static("A `{$name}` ability already exists for slug `{$slug}`.");
	}
}

