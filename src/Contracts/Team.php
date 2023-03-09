<?php

namespace BigFiveEdition\Permission\Contracts;

use BigFiveEdition\Permission\Exceptions\TeamDoesNotExist;

interface Team
{
	/**
	 * Find a team by its name and guard name.
	 *
	 * @param string $slug
	 * @return Team
	 *
	 * @throws TeamDoesNotExist
	 */
	public static function findBySlug(string $slug): self;

	/**
	 * Find a team by its name and guard name.
	 *
	 * @param string $name
	 * @return Team
	 *
	 * @throws TeamDoesNotExist
	 */
	public static function findByName(string $name): self;

	/**
	 * Find a team by its id and guard name.
	 *
	 * @param int $id
	 * @return Team
	 *
	 * @throws TeamDoesNotExist
	 */
	public static function findById(int $id): self;

	/**
	 * Find or create a team by its name and guard name.
	 *
	 * @param string $name
	 * @param string $slug
	 * @return Team
	 */
	public static function findOrCreate(string $name, string $slug): self;
}
