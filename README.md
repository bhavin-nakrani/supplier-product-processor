# Supplier Product List Processor

## Installation
1. Clone the repository.
2. Run `composer dump-autoload` or Run `composer install`.

## Generate File

*Note: Delete `/files/combination_count.csv` file for generate csv from scratch. If you are not deleting this file then new output will append in existing file and file size become high with duplicate records.*


```bash
php parser.php --file=products_comma_separated.csv --unique-combinations=combination_count.csv
```

## Run Test

```
composer test

or

./vendor/bin/phpunit
```

## What is included in this project
#### Folder Strucure
* Folders are files, src, tests. Where files is for store csv files. Everything related PHP logic in src folder, and tests folder contains test file.
* `parser.php` is main file and it should use to run in terminal. Command is mentioned above step.
* `.gitignore` file is for remove vendor and cache folder from repository
* `phpunit.xml` file is for setup setting of phpunit. which is used for run test case. `composer test` command is for run all test from tests folder.
* `composer.json` contains all require dev and tests setting and packages.
* In `src` folder:
    - `Product.php` and `ProductProcessor.php` file is for create Product object with all fields.
    - `CsvParser.php` and `ParserInterface.php` file is for provide parse function. It is only for read file and provide array.
    - `UniqueCombinationWriter.php` file is for open/create file and put header and row from output logic.
* In root path, `parser.php` contains all logic with try/catch condition.
    - Added chunk logic, Read 100 rows at a time and free memory in each loop to reduce memory consume.
* In `tests` folder,
    - Created `ParserTest.php` file which contains `setUp` method which is for generate mock object from different classes.
    - method `testParserProcessesFileAndWritesUniqueCombinations()` is for test `parse.php` logic. 
    For example:
        - `write` function must called 2 times
        - each chunk loop, given object must match with `test_output.csv` file.
    * I have used `phpunit` for create test case. whatever method name is start with `test` word it will consider in test command.