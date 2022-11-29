# Package Tracking Utilities

[![Latest Version on Packagist](https://img.shields.io/packagist/v/rpungello/package-tracking.svg?style=flat-square)](https://packagist.org/packages/rpungello/package-tracking)
[![Tests](https://github.com/rpungello/package-tracking/actions/workflows/run-tests.yml/badge.svg?branch=main)](https://github.com/rpungello/package-tracking/actions/workflows/run-tests.yml)
[![Total Downloads](https://img.shields.io/packagist/dt/rpungello/package-tracking.svg?style=flat-square)](https://packagist.org/packages/rpungello/package-tracking)

Provides helpful utilities for dealing with tracking numbers

## Installation

You can install the package via composer:

```bash
composer require rpungello/package-tracking
```

## Usage

```php
$instance = new \Rpungello\PackageTracking\PackageTracking();
$package = $instance->parseTrackingNumber('1Z12345E0305271640');
$packages = $instance->parsePackages("First line has a tracking number 1Z12345E0305271640 and some other text\nSecond line has 1Z12345E0205271688");
```

## Testing

```bash
composer test
```

## Changelog

Please see [CHANGELOG](CHANGELOG.md) for more information on what has changed recently.

## Contributing

Please see [CONTRIBUTING](https://github.com/spatie/.github/blob/main/CONTRIBUTING.md) for details.

## Security Vulnerabilities

Please review [our security policy](../../security/policy) on how to report security vulnerabilities.

## Credits

- [Rob Pungello](https://github.com/rpungello)
- [All Contributors](../../contributors)

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
