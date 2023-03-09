<?php

return [

	'default_teams' => [],
	'default_roles' => ['sysadmin', 'admin', 'pro_user', 'regular_user'],
	'ability_operations' => ['read_all', 'read_one', 'create_one', 'update_one', 'delete_one'],
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
