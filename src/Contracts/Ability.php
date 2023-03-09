<?php

namespace BigFiveEdition\Permission\Contracts;

use BigFiveEdition\Permission\Exceptions\AbilityDoesNotExist;

interface Ability
{
	/**
	 * Find a ability by its name and guard name.
	 *
	 * @param string $slug
	 * @return Ability
	 *
	 * @throws AbilityDoesNotExist
	 */
	public static function findBySlug(string $slug): self;

	/**
	 * Find a ability by its name and guard name.
	 *
	 * @param string $name
	 * @return Ability
	 *
	 * @throws AbilityDoesNotExist
	 */
	public static function findByName(string $name): self;

	/**
	 * Find a ability by its id and guard name.
	 *
	 * @param int $id
	 * @return Ability
	 *
	 * @throws AbilityDoesNotExist
	 */
	public static function findById(int $id): self;

	/**
	 * Find or create a ability by its name and guard name.
	 *
	 * @param string $name
	 * @param string $slug
	 * @return Ability
	 */
	public static function findOrCreate(string $name, string $slug): self;
}
