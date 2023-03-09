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
