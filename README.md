# Generate bitkub web api (v1.0.0)
For repository patten design under `laravel framework`
# Feature 
- can install via `composer`
- can create update delete search all ,where like ,find by id , filter language 
- can response json format [not code API standard] 
- can generate request file : namespace}Request
- can generate service
- can generate controller 
- can generate route and mapping auto to controller
# Installation 
```php
composer require bitkub1/Webapi
```
# To register a service provider.
add the Provider to the providers array in bootstrap/app.php
```php
$app->register(Webapi\Providers\GenerateWebapiProvider::class);
```
# Recommend
You can add helpers folder in app folder and add helpers.php
```php
<?php
if ( ! function_exists('config_path'))
{
    /**
     * Get the configuration path.
     *
     * @param  string $path
     * @return string
     */
    function config_path($path = '')
    {
        return app()->basePath() . '/config' . ($path ? '/' . $path : $path);
    }
}
```
then add this to composer.json
```php
"autoload": {
    "psr-4": {
        "App\\": "app/"
    },
    "files": [
        "app/helpers/helpers.php"
    ]
},
```
then run,
```
composer dump-autoload
```

# Command
```php
$ php artisan bitkubweb:genfile
```
