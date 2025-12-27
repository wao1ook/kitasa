# Multi-Stage Phone Number Authentication for Laravel Apps

[![Latest Stable Version](https://poser.pugx.org/emanate/kitasa/v)](https://packagist.org/packages/emanate/kitasa)
[![Total Downloads](https://poser.pugx.org/emanate/kitasa/downloads)](https://packagist.org/packages/emanate/kitasa)
[![License](https://poser.pugx.org/emanate/kitasa/license)](https://packagist.org/packages/emanate/kitasa)

This package provides a multi-stage phone number authentication system for Filament panels, including OTP-based password resets.

## Installation

You can install the package via composer:

```bash
composer require emanate/kitasa
```

You can publish and run the migrations with:

```bash
php artisan vendor:publish --tag="kitasa-migrations"
php artisan migrate
```

You can publish the config file with:

```bash
php artisan vendor:publish --tag="kitasa-config"
```

This is the contents of the published config file:

```php
return [
    'phone_column' => 'phone_number',

    'otp' => [
        'expiry' => 10, // minutes
        'table' => 'kitasa_otps',
    ],
];
```

Optionally, you can publish the translations using

```bash
php artisan vendor:publish --tag="kitasa-translations"
```


## Usage

To integrate the phone authentication system into your Filament panel, register the `KitasaPlugin` in your panel configuration:

```php
use Emanate\Kitasa\KitasaPlugin;

public function panel(Panel $panel): Panel
{
    return $panel
        ->plugins([
            KitasaPlugin::make(),
        ]);
}
```

This will automatically override the default Filament login, password reset request, and password reset pages to use a phone-based, multi-stage flow.


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

- [Emanate Software](https://github.com/emanate)
- [All Contributors](../../contributors)

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
