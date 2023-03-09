<?php

namespace BigFiveEdition\Permission\Exceptions;

use InvalidArgumentException;

class TeamDoesNotExist extends InvalidArgumentException
{
	public static function create(string $name, string $slug = '')
	{
		return new static("There is no team named `{$name}` for slug `{$slug}`.");
	}

	public static function withId(int $id, string $slug = '')
	{
		return new static("There is no [team] with id `{$id}` for slug `{$slug}`.");
	}

	public static function withSlug(string $slug = '')
	{
		return new static("There is no [team] for slug `{$slug}`.");
	}
}

