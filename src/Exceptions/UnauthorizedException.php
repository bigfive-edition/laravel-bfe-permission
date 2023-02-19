<?php

namespace BigFiveEdition\Permission\Exceptions;

use Symfony\Component\HttpKernel\Exception\HttpException;

class UnauthorizedException extends HttpException
{
	private $requiredTeams = [];
	private $requiredRoles = [];
	private $requiredAbilities = [];

	public static function forTeams(array $teams): self
	{
		$message = 'User does not have the right teams.';

		if (config('bfe-permission.display_team_in_exception')) {
			$message .= ' Necessary teams are ' . implode(', ', $teams);
		}

		$exception = new static(403, $message, null, []);
		$exception->requiredTeams = $teams;

		return $exception;
	}

	public static function forRoles(array $roles): self
	{
		$message = 'User does not have the right roles.';

		if (config('bfe-permission.display_role_in_exception')) {
			$message .= ' Necessary roles are ' . implode(', ', $roles);
		}

		$exception = new static(403, $message, null, []);
		$exception->requiredRoles = $roles;

		return $exception;
	}

	public static function forAbilities(array $abilities): self
	{
		$message = 'User does not have the right abilities.';

		if (config('bfe-permission.display_ability_in_exception')) {
			$message .= ' Necessary abilities are ' . implode(', ', $abilities);
		}

		$exception = new static(403, $message, null, []);
		$exception->requiredAbilities = $abilities;

		return $exception;
	}

	public static function forRolesOrAbilities(array $rolesOrAbilities): self
	{
		$message = 'User does not have any of the necessary access rights.';

		if (config('bfe-permission.display_ability_in_exception') && config('bfe-permission.display_role_in_exception')) {
			$message .= ' Necessary roles or abilities are ' . implode(', ', $rolesOrAbilities);
		}

		$exception = new static(403, $message, null, []);
		$exception->requiredAbilities = $rolesOrAbilities;

		return $exception;
	}

	public static function notLoggedIn(): self
	{
		return new static(403, 'User is not logged in.', null, []);
	}

	public function getRequiredTeams(): array
	{
		return $this->requiredTeams;
	}

	public function getRequiredRoles(): array
	{
		return $this->requiredRoles;
	}

	public function getRequiredAbilities(): array
	{
		return $this->requiredAbilities;
	}
}
