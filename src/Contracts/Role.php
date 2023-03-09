<?php

namespace BigFiveEdition\Permission\Contracts;

use BigFiveEdition\Permission\Exceptions\RoleDoesNotExist;

interface Role
{
	/**
	 * Find a role by its name and guard name.
	 *
	 * @param string $slug
	 * @return Role
	 *
	 * @throws RoleDoesNotExist
	 */
	public static function findBySlug(string $slug): self;

	/**
	 * Find a role by its name and guard name.
	 *
	 * @param string $name
	 * @return Role
	 *
	 * @throws RoleDoesNotExist
	 */
	public static function findByName(string $name): self;

	/**
	 * Find a role by its id and guard name.
	 *
	 * @param int $id
	 * @return Role
	 *
	 * @throws RoleDoesNotExist
	 */
	public static function findById(int $id): self;

	/**
	 * Find or create a role by its name and guard name.
	 *
	 * @param string $name
	 * @param string $slug
	 * @return Role
	 */
	public static function findOrCreate(string $name, string $slug): self;
}
