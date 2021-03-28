# Create scripts

[![Latest Version on Packagist](https://img.shields.io/packagist/v/narcisonunez/laravel-scripts.svg?style=flat-square)](https://packagist.org/packages/narcisonunez/laravel-scripts)
[![GitHub Tests Action Status](https://img.shields.io/github/workflow/status/narcisonunez/laravel-scripts/run-tests?label=tests)](https://github.com/narcisonunez/laravel-scripts/actions?query=workflow%3ATests+branch%3Amaster)
[![GitHub Code Style Action Status](https://img.shields.io/github/workflow/status/narcisonunez/laravel-scripts/Check%20&%20fix%20styling?label=code%20style)](https://github.com/narcisonunez/laravel-scripts/actions?query=workflow%3A"Check+%26+fix+styling"+branch%3Amaster)
[![Total Downloads](https://img.shields.io/packagist/dt/narcisonunez/laravel-scripts.svg?style=flat-square)](https://packagist.org/packages/narcisonunez/laravel-scripts)

![Banner image](https://banners.beyondco.de/Laravel%20Scripts.png?theme=light&packageManager=composer+require&packageName=narcisonunez%2Flaravel-scripts&pattern=topography&style=style_2&description=&md=1&showWatermark=1&fontSize=100px&images=https%3A%2F%2Flaravel.com%2Fimg%2Flogomark.min.svg
)

Laravel Scripts allows you to create scripts and manage if that script can run or not.

## Installation

You can install the package via composer:

```bash
composer require narcisonunez/laravel-scripts
```

You can publish and run the migrations with:

```bash
php artisan vendor:publish --provider="Narcisonunez\LaravelScripts\LaravelScriptsServiceProvider" --tag="scripts-migrations"
php artisan migrate
```

## Terminal Usage
###Create a script
Use the `--force` option to override the existing class
```bash
php artisan scripts:make ScriptName
```

###Features
You can customize the script by overriding the following properties:

* Numbers of times you can run the script.
```php
/**
 * @var int
 */
public int $allowedRuns = 1;
```
This will ensure you are only running a script one time.
By default, It is unlimited (`0`)

* Run the script inside a transaction.
```php
/**
 * @var bool
 */
public bool $runAsTransaction = true;
```
This will eun you run method inside a transaction.
By default, It is `false`.

* Numbers of times you can run the script.
```php
/**
* @var array
*/
public array $dependenciesValues = [
    'email : The email for the notification',
    'name? : Name of the person running the script',
    'role?'
];
```
The format for the dependencies is:
`fieldName : Description`. Using `?` in the `fieldName` will make it optional

You can access this values in your script class using `$this->dependencies->fieldName`

###Run a script
```bash
php artisan scripts:run ScriptName
```
Use the `--interactive` option to use your `depencenciesValues`. This will ask you to enter all the values dynamically.

You can access this values in your script class using `$this->dependencies->fieldName`

###See history
```bash
php artisan scripts:history [--limit=30] [--script=ScriptName]
```
By default `--limit` is set to `10`

## UI Usage
Add this line to the routes/web.php file
```php
Route::laravelScripts('scripts');
```
Go to your browser `/scripts` to access the Administration.

* You are not able to create scripts from the UI.

## Testing
```bash
composer test
```

## Changelog

Please see [CHANGELOG](CHANGELOG.md) for more information on what has changed recently.

## Contributing

Please see [CONTRIBUTING](.github/CONTRIBUTING.md) for details.

## Security Vulnerabilities

Please review [our security policy](../../security/policy) on how to report security vulnerabilities.

## Credits

- [Narciso Nunez](https://github.com/narcisonunez)
- [All Contributors](../../contributors)

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
