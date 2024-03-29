<?php

namespace BigFiveEdition\Permission\Exceptions;

use InvalidArgumentException;

class BfeRoleAlreadyExists extends InvalidArgumentException
{
	public static function create(string $name, string $slug = '')
	{
		return new static("A `{$name}` role already exists for slug `{$slug}`.");
	}
}

