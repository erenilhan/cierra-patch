# Cierra - Challenge Task [ Cierra Patch ]

[![Latest Version on Packagist](https://img.shields.io/packagist/v/erenilhan/cierra-patch.svg?style=flat-square)](https://packagist.org/packages/erenilhan/cierra-patch)
[![GitHub Tests Action Status](https://img.shields.io/github/actions/workflow/status/erenilhan/cierra-patch/run-tests.yml?branch=main&label=tests&style=flat-square)](https://github.com/erenilhan/cierra-patch/actions?query=workflow%3Arun-tests+branch%3Amain)
[![GitHub Code Style Action Status](https://img.shields.io/github/actions/workflow/status/erenilhan/cierra-patch/fix-php-code-style-issues.yml?branch=main&label=code%20style&style=flat-square)](https://github.com/erenilhan/cierra-patch/actions?query=workflow%3A"Fix+PHP+code+style+issues"+branch%3Amain)
[![Total Downloads](https://img.shields.io/packagist/dt/erenilhan/cierra-patch.svg?style=flat-square)](https://packagist.org/packages/erenilhan/cierra-patch)

This is where your description should go. Limit it to a paragraph or two. Consider adding a small example.


## Installation

You can install the package via composer:

```bash
composer require " erenilhan/cierra-patch @dev"
```

You can publish and run the migrations with:

```bash
php artisan vendor:publish --tag="cierra-patch-migrations"
php artisan migrate
```


## Usage
 Create a patch
```bash 
php artisan cierra:make-patch {patch_name} 
```
Run patch
```bash
php artisan cierra:patch
```
Status of patch
```bash
php artisan cierra:patch-status
```


## Testing

```bash
composer test
```

## Changelog

Please see [CHANGELOG](CHANGELOG.md) for more information on what has changed recently.

## Contributing

Please see [CONTRIBUTING](CONTRIBUTING.md) for details.

## Security Vulnerabilities

Please review [our security policy](../../security/policy) on how to report security vulnerabilities.

## Credits

- [Eren İlhan](https://github.com/erenilhan)
- [All Contributors](../../contributors)

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
