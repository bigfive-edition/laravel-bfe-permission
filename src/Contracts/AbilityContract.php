<?php

namespace BigFiveEdition\Permission\Contracts;

use BigFiveEdition\Permission\Exceptions\BfeAbilityDoesNotExist;
use Illuminate\Database\Eloquent\Relations\HasMany;

interface AbilityContract
{
	/**
	 * Find a ability by its name and guard name.
	 *
	 * @param string $slug
	 * @return ?AbilityContract
	 *
	 * @throws BfeAbilityDoesNotExist
	 */
	public static function findBySlug(string $slug): ?self;

	/**
	 * Find a ability by its name and guard name.
	 *
	 * @param string $name
	 * @return ?AbilityContract
	 *
	 * @throws BfeAbilityDoesNotExist
	 */
	public static function findByName(string $name): ?self;

	/**
	 * Find a ability by its id and guard name.
	 *
	 * @param int $id
	 * @return ?AbilityContract
	 *
	 * @throws BfeAbilityDoesNotExist
	 */
	public static function findById(int $id): ?self;

	/**
	 * Find or create a ability by its name and guard name.
	 *
	 * @param string $name
	 * @param string $slug
	 * @return ?AbilityContract
	 */
	public static function findOrCreate(string $name, string $slug, ?string $resource): ?self;

	public function ability_models(): HasMany;
}
