# Drupal conventions

This tool will check your code against Drupal's coding standard.

It's based on [GrumPHP](https://github.com/phpro/grumphp) and comes with a default configuration tailored for Drupal development.

The following checks are triggered:
* [Drupal coder](https://www.drupal.org/project/coder) code sniffer's checks
* Custom [PHP CS Fixer](https://github.com/FriendsOfPHP/PHP-CS-Fixer) configuration
* Git commit message checks

## Installation

```shell
composer require drupol/drupal-conventions --dev
```

### If you're not using GrumPHP

Manually add to your `composer.json` file:

```yaml
    "extra": {
        "grumphp": {
            "config-default-path": "vendor/drupol/drupal-conventions/config/drupal/grumphp.yml"
        }
    }
```

### If you're using GrumPHP already

Edit the file `grumphp.yml.dist` or `grumphp.yml` and add on the top it:

```yaml
imports:
  - { resource: vendor/drupol/drupal-conventions/config/drupal/grumphp.yml }
```

## Contributing

Feel free to contribute to this library by sending Github pull requests. I'm quite reactive :-)
