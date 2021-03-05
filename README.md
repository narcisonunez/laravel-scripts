# Create scripts

[![Latest Version on Packagist](https://img.shields.io/packagist/v/narcisonunez/laravel-scripts.svg?style=flat-square)](https://packagist.org/packages/narcisonunez/laravel-scripts)
[![GitHub Tests Action Status](https://img.shields.io/github/workflow/status/narcisonunez/laravel-scripts/run-tests?label=tests)](https://github.com/narcisonunez/laravel-scripts/actions?query=workflow%3ATests+branch%3Amaster)
[![GitHub Code Style Action Status](https://img.shields.io/github/workflow/status/narcisonunez/laravel-scripts/Check%20&%20fix%20styling?label=code%20style)](https://github.com/narcisonunez/laravel-scripts/actions?query=workflow%3A"Check+%26+fix+styling"+branch%3Amaster)
[![Total Downloads](https://img.shields.io/packagist/dt/narcisonunez/laravel-scripts.svg?style=flat-square)](https://packagist.org/packages/narcisonunez/laravel-scripts)


This is where your description should go. Limit it to a paragraph or two. Consider adding a small example.

## Installation

You can install the package via composer:

```bash
composer require narcisonunez/laravel-scripts
```

You can publish and run the migrations with:

```bash
php artisan vendor:publish --provider="Narcisonunez\LaravelScripts\LaravelScriptsServiceProvider" --tag="laravel-scripts-migrations"
php artisan migrate
```

You can publish the config file with:
```bash
php artisan vendor:publish --provider="Narcisonunez\LaravelScripts\LaravelScriptsServiceProvider" --tag="laravel-scripts-config"
```

This is the contents of the published config file:

```php
return [
];
```

## Usage

```php
$laravelScripts = new Narcisonunez\LaravelScripts();
echo $laravel-scripts->echoPhrase('Hello, Narcisonunez!');
```

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
