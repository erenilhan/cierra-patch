# Cierra - Challenge Task [ Cierra Patch ]
Cierra Patch is a Laravel package that provides patch management functionality. It allows you to create, manage, and apply patches to your application.

Installation
## Installation

You can install the package via Composer:

```bash
composer require "erenilhan/cierra-patch @dev"
```

You can publish and run the migrations with:

```bash
php artisan vendor:publish --tag="cierra-patch-migrations"
php artisan migrate
```


## Usage
### Create a patch

```bash 
php artisan cierra:make-patch {patch_name} 
```
If you don't provide a **{patch_name}**, the command will prompt you to enter the name of the patch.

### Check Patch Status
To check the status of a patch, use the following command:
```bash
php artisan cierra:patch-status
```
### Apply Patches
To apply patches to your application, run the following command:

```bash 
php artisan cierra:patch
```


## Testing

```bash
composer test
```

## Changelog

Please see [CHANGELOG](CHANGELOG.md) for more information on what has changed recently.

## Contributing

Please see [CONTRIBUTING](CONTRIBUTING.md) for details.


## Credits

- [Eren İlhan](https://github.com/erenilhan) / [erenilhan.com](https://erenilhan.com)

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
