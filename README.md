# Supplier Product List Processor

## Requirements
- PHP 7+ must be installed.
- Composer for autoloading.

## Installation
1. Clone the repository.
2. Run `composer dump-autoload` or Run `composer install`.

## Usage
```bash
php parser.php --file=products_comma_separated.csv --unique-combinations=combination_count.csv
```

## Run Test

```
composer test

or

./vendor/bin/phpunit
```
