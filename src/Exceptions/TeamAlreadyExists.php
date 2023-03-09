<?php

namespace BigFiveEdition\Permission\Exceptions;

use InvalidArgumentException;

class TeamAlreadyExists extends InvalidArgumentException
{
	public static function create(string $name, string $slug = '')
	{
		return new static("A `{$name}` team already exists for slug `{$slug}`.");
	}
}

