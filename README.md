# Drupal conventions

This tool will check your code against Drupal's coding standard.

It's based on [GrumPHP](https://github.com/phpro/grumphp) and comes with a default configuration tailored for Drupal development.

The following checks are triggered:
* [Drupal coder](https://www.drupal.org/project/coder) code sniffer's checks
* Custom [PHP CS Fixer](https://github.com/FriendsOfPHP/PHP-CS-Fixer) configuration
* Git commit message checks

Drupal 7 and 8 are supported.

## Installation

```shell
composer require drupol/drupal-conventions --dev
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

## Contributing

Feel free to contribute to this library by sending Github pull requests. I'm quite reactive :-)
