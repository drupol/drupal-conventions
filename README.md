[![Latest Stable Version](https://img.shields.io/packagist/v/drupol/drupal-conventions.svg?style=flat-square)](https://packagist.org/packages/drupol/drupal-conventions)
 [![Stars](https://img.shields.io/github/stars/drupol/drupal-conventions.svg?style=flat-square)](https://github.com/drupol/drupal-conventions)
 [![Total Downloads](https://img.shields.io/packagist/dt/drupol/drupal-conventions.svg?style=flat-square)](https://packagist.org/packages/drupol/drupal-conventions)
 [![Build Status](https://img.shields.io/travis/drupol/drupal-conventions/master.svg?style=flat-square)](https://travis-ci.org/drupol/drupal-conventions)
 [![License](https://img.shields.io/github/license/drupol/drupal-conventions.svg?style=flat-square)](https://packagist.org/packages/drupol/drupal-conventions)
 [![Say Thanks!](https://img.shields.io/badge/Say-thanks-brightgreen.svg?style=flat-square)](https://saythanks.io/to/drupol)
 [![Donate!](https://img.shields.io/badge/Donate-Paypal-brightgreen.svg?style=flat-square)](https://paypal.me/drupol)

# Drupal conventions

This tool will check your code against Drupal's coding standard.

It's based on [GrumPHP](https://github.com/phpro/grumphp) and comes with a default configuration tailored for Drupal development.

The following checks are triggered:
* [Drupal coder](https://www.drupal.org/project/coder) code sniffer's checks
* Custom [PHP CS Fixer](https://github.com/FriendsOfPHP/PHP-CS-Fixer) configuration
* PHPLint
* YAMLlint
* JSONlint

Drupal 7 and 8 are supported.

## Installation

```shell
composer require --dev drupol/drupal-conventions
```

### If you're not using GrumPHP

Manually add to your `composer.json` file:

#### Drupal 8
```yaml
    "extra": {
        "grumphp": {
            "config-default-path": "vendor/drupol/drupal-conventions/config/drupal8/grumphp.yml"
        }
    }
```
#### Drupal 7
```yaml
    "extra": {
        "grumphp": {
            "config-default-path": "vendor/drupol/drupal-conventions/config/drupal7/grumphp.yml"
        }
    }
```

### If you're using GrumPHP already

Edit the file `grumphp.yml.dist` or `grumphp.yml` and add on the top it:

#### Drupal 8
```yaml
imports:
  - { resource: vendor/drupol/drupal-conventions/config/drupal8/grumphp.yml }
```
#### Drupal 7
```yaml
imports:
  - { resource: vendor/drupol/drupal-conventions/config/drupal7/grumphp.yml }
```

To add an extra Grumphp task:

```yaml
imports:
  - { resource: vendor/drupol/drupal-conventions/config/drupal7/grumphp.yml }

parameters:
  extra_tasks:
    phpunit:
      always_execute: false
```

In conjunction with `extra_tasks`, use `skip_tasks` to skip tasks if needed.

## Contributing

Feel free to contribute to this library by sending Github pull requests. I'm quite reactive :-)
