<?php

namespace BigFiveEdition\Permission\Contracts;

use BigFiveEdition\Permission\Exceptions\BfeTeamDoesNotExist;
use Illuminate\Database\Eloquent\Relations\HasMany;

interface TeamContract
{
	/**
	 * Find a team by its name and guard name.
	 *
	 * @param string $slug
	 * @return ?TeamContract
	 *
	 * @throws BfeTeamDoesNotExist
	 */
	public static function findBySlug(string $slug): ?self;

	/**
	 * Find a team by its name and guard name.
	 *
	 * @param string $name
	 * @return ?TeamContract
	 *
	 * @throws BfeTeamDoesNotExist
	 */
	public static function findByName(string $name): ?self;

	/**
	 * Find a team by its id and guard name.
	 *
	 * @param int $id
	 * @return ?TeamContract
	 *
	 * @throws BfeTeamDoesNotExist
	 */
	public static function findById(int $id): ?self;

	/**
	 * Find or create a team by its name and guard name.
	 *
	 * @param string $name
	 * @param string $slug
	 * @return ?TeamContract
	 */
	public static function findOrCreate(string $name, string $slug): ?self;

	public function team_models(): HasMany;
}
