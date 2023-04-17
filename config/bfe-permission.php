<?php

return [

	'default_teams' => [],
	'default_roles' => ['sysadmin', 'application_admin', 'community_manager', 'client'],
	'ability_operations' => ['read_all', 'read_all_owned', 'read', 'read_owned', 'create', 'update', 'update_owned', 'delete', 'delete_owned'],
	'ability_resources' => [
		\BigFiveEdition\Permission\Models\Team::class,
		\BigFiveEdition\Permission\Models\TeamModel::class,
		\BigFiveEdition\Permission\Models\Role::class,
		\BigFiveEdition\Permission\Models\RoleModel::class,
		\BigFiveEdition\Permission\Models\Ability::class,
		\BigFiveEdition\Permission\Models\AbilityModel::class,
	],
	'routes_prefix' => '',
];
