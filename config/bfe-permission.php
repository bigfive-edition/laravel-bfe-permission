<?php

return [

	'default_teams' => [],
	'default_roles' => ['sysadmin', 'admin', 'pro_user', 'regular_user'],
	'ability_operations' => ['read_all', 'read_one', 'read_owned', 'create_one', 'update_one', 'update_owned', 'delete_one', 'delete_owned'],
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
