<?php

namespace BigFiveEdition\Permission\Exceptions;

use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;

class BfeAccessDeniedException extends AccessDeniedHttpException
{
	private $requiredTeams = [];
	private $requiredRoles = [];
	private $requiredAbilities = [];

	public static function forTeams($teams): ?self
	{
		if (stripos($teams, '|')) {
			$teams = is_array($teams) ? $teams : array_map('trim', explode('|', $teams));
		} else if (stripos($teams, '&')) {
			$teams = is_array($teams) ? $teams : array_map('trim', explode('&', $teams));
		} else {
			$teams = is_array($teams) ? $teams : [$teams];
		}
		$message = 'User does not have the right teams.';

		if (config('bfe-permission.display_team_in_exception', true)) {
			$message .= ' Necessary teams are ' . implode(', ', $teams);
		}

		$exception = new static($message, null, 403);
		$exception->requiredTeams = $teams;

		return $exception;
	}

	public static function forRoles($roles): ?self
	{
		if (stripos($roles, '|')) {
			$roles = is_array($roles) ? $roles : array_map('trim', explode('|', $roles));
		} else if (stripos($roles, '&')) {
			$roles = is_array($roles) ? $roles : array_map('trim', explode('&', $roles));
		} else {
			$roles = is_array($roles) ? $roles : [$roles];
		}
		$message = 'User does not have the right roles.';

		if (config('bfe-permission.display_role_in_exception', true)) {
			$message .= ' Necessary roles are ' . implode(', ', $roles);
		}

		$exception = new static($message, null, 403);
		$exception->requiredRoles = $roles;

		return $exception;
	}

	public static function forAbilities($abilities): ?self
	{
		if (stripos($abilities, '|')) {
			$abilities = is_array($abilities) ? $abilities : array_map('trim', explode('|', $abilities));
		} else if (stripos($abilities, '&')) {
			$abilities = is_array($abilities) ? $abilities : array_map('trim', explode('&', $abilities));
		} else {
			$abilities = is_array($abilities) ? $abilities : [$abilities];
		}
		$message = 'User does not have the right abilities.';

		if (config('bfe-permission.display_ability_in_exception', true)) {
			$message .= ' Necessary abilities are ' . implode(', ', $abilities);
		}

		$exception = new static($message, null, 403);
		$exception->requiredAbilities = $abilities;

		return $exception;
	}

	public static function forRolesOrAbilities(array $rolesOrAbilities): ?self
	{
		if (stripos($rolesOrAbilities, '|')) {
			$rolesOrAbilities = is_array($rolesOrAbilities) ? $rolesOrAbilities : array_map('trim', explode('|', $rolesOrAbilities));
		} else if (stripos($rolesOrAbilities, '&')) {
			$rolesOrAbilities = is_array($rolesOrAbilities) ? $rolesOrAbilities : array_map('trim', explode('&', $rolesOrAbilities));
		} else {
			$rolesOrAbilities = is_array($rolesOrAbilities) ? $rolesOrAbilities : [$rolesOrAbilities];
		}
		$message = 'User does not have any of the necessary access rights.';

		if (config('bfe-permission.display_ability_in_exception', true) && config('bfe-permission.display_role_in_exception', true)) {
			$message .= ' Necessary roles or abilities are ' . implode(', ', $rolesOrAbilities);
		}

		$exception = new static($message, null, 403);
		$exception->requiredAbilities = $rolesOrAbilities;

		return $exception;
	}

	public static function notLoggedIn(): ?self
	{
		return new static('User is not logged in.', null, 403);
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
