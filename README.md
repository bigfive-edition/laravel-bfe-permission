# BFE Permissions

## Documentation, Installation, and Usage Instructions

To get started with using the package, we'll install it using the following command:

add the private repository in your composer.json file
```json
"repositories": [
	{
		"type": "vcs",
		"url": "git@gitlab.bfedition.com:bigcity/bigcity-instances/bigfiveedition-laravel-permission.git"
	}
],
```

```php
composer require bigfive-edition/laravel-bfe-permission
```
Now that we've installed the package, we'll need to publish the database migration and config file:

```php
php artisan bfe-permission:install
```
We can now run the migrations to create the new tables in our database:

```php
php artisan migrate
```
Assuming that we are using the default config values and haven't changed anything in the package's config/bfe-permission.php, 
we should now have five new tables in our database:


We can also generate the default 

```php
php artisan bfe-permission:generate-teams
php artisan bfe-permission:generate-roles
php artisan bfe-permission:generate-abilities
```

## Http Routes
it comes with default routes for managing team, roles, and abilities
check the postman collection 

```
{routes_prefix}/bfe-permissions/teams
{routes_prefix}/bfe-permissions/teams/{team_id}/models

{routes_prefix}/bfe-permissions/roles
{routes_prefix}/bfe-permissions/roles/{role_id}/models

{routes_prefix}/bfe-permissions/abilities
{routes_prefix}/bfe-permissions/abilities/{ability_id}/models
```

## Http Route Middlewares
Adding routes middlewares as follow.
Note that the `|` is for OR operations and the `&` is for AND operations

```
bfe-permission.teams:waiters|managers
bfe-permission.teams:waiters&managers

bfe-permission.roles:admin|system_admin
bfe-permission.roles:admin&system_admin

bfe-permission.abilities:read_all_users|create_one_vehicle
bfe-permission.abilities:read_all_users&create_one_vehicle
bfe-permission.abilities:read_all_users|create_one_vehicle,{resource_class},{resource_id}
bfe-permission.abilities:read_all_users&create_one_vehicle,{resource_class},{resource_id}
```
