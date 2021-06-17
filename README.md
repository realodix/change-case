# Change Case

![PHPVersion](https://img.shields.io/badge/PHP-^7.4|^8-777BB4.svg?style=flat-square)
[![Tests](https://github.com/realodix/change-case/actions/workflows/tests.yml/badge.svg)](https://github.com/realodix/change-case/actions/workflows/tests.yml)
[![GitHub license](https://img.shields.io/github/license/realodix/change-case)](/LICENSE)

> Transform a string between `camelCase`, `PascalCase`, `Capital Case`, `snake_case`, `param-case`, `CONSTANT_CASE` and others.

## Installation

You can install the package via composer:

```
composer require realodix/change-case

// Dev-build
composer require realodix/change-case 1.x-dev
```

## Usage

```php
use Realodix\ChangeCase\ChangeCase;
```

### Core

- [`camelCase`](#camelcase)
- [`Capital Case`](#capital-case)
- [`CONSTANT_CASE`](#constant_case)
- [`dot.case`](#dotcase)
- [`Header-Case`](#header-case)
- [`no case`](#no-case)
- [`PascalCase`](#pascalcase)
- [`path/case`](#pathcase)
- [`Sentence case`](#sentence-case)
- [`snake_case`](#snake_case)
- [`spinal-case`](#spinal-case)

#### camelCase

> Transform into a string with the separator denoted by the next word capitalized.

Options
- separateNumbers

```php
$cc = new ChangeCase;

$cc->camelCase('test string');
// 'testString'

$cc->camelCase('1twoThree');
// '1twoThree'
$cc->camelCase('1twoThree', ['separateNumbers' => true]);
// '1TwoThree'
```

#### Capital Case

> Transform into a space separated string with each word capitalized.

```php
$cc = new ChangeCase;
$cc->capitalCase('test string');
// 'Test String'
```

#### CONSTANT_CASE

> Transform into upper case string with an underscore between words.

```php
$cc = new ChangeCase;
$cc->constantCase('test string');
// 'TEST_STRING'
```

#### dot.case

> Transform into a lower case string with a period between words.

```php
$cc = new ChangeCase;
$cc->dotCase('test string');
// 'test.string'
```

#### Header-Case

> Transform into a dash separated string of capitalized words.

```php
$cc = new ChangeCase;
$cc->headerCase('test string');
// 'Test-String'
```

#### no case

> Transform into a lower cased string with spaces between words.

Options
- delimiter
- splitRegexp
- stripRegexp
- separateNumbers

```php
$cc = new ChangeCase;

$cc->noCase('testString');
// 'test string'

$customSplitRegexp
$cc->noCase('minifyURLs', ['splitRegexp' => $customSplitRegexp]);
// 'minify urls'
$cc->noCase('minifyURLs', ['delimiter' => '-', 'splitRegexp' => $customSplitRegexp]);
// 'minify-urls'

$cc->noCase('Foo123Bar')
// foo123 bar
$cc->noCase('Foo123Bar', ['separateNumbers' => true])
// foo 123 bar
```

#### PascalCase

> Transform into a string of capitalized words without separators.


```php
$cc = new ChangeCase;
$cc->pascalCase('test string');
// 'TestString'
```

#### path/case

> Transform into a lower case string with slashes between words.

```php
$cc = new ChangeCase;
$cc->pathCase('test string');
// 'test/string'
```

#### Sentence case

> Transform into a lower case with spaces between words, then capitalize the string.

```php
$cc = new ChangeCase;
$cc->sentenceCase('testString');
// 'Test string'
```

#### snake_case

> Transform into a lower case string with underscores between words.

Options
- separateNumbers

```php
$cc = new ChangeCase;

$cc->snakeCase('test string');
// 'test_string'

$cc->snakeCase('Foo123Bar');
// 'foo123_bar'
$cc->snakeCase('Foo123Bar', ['separateNumbers' => true]);
// 'foo_123_bar'
```

#### spinal-case

> Transform into a lower cased string with dashes between words.

Options
- separateNumbers

```php
$cc = new ChangeCase;

$cc->spinalCase('test string');
// 'test-string'

$cc->spinalCase('Foo123Bar');
// 'foo123-bar'
$cc->spinalCase('Foo123Bar', ['separateNumbers' => true]);
// 'foo-123-bar'
```

### Other Case Utilities

- [`swapCase`](#swapcase)
- [`titleCase`](#titlecase)

#### swapCase

> Transform a string by swapping every character from upper to lower case, or lower to upper case.

```php
$cc = new ChangeCase;
$cc->swapCase('Test String');
// 'tEST sTRING'
```

#### titleCase

> Transform a string into title case following English rules.

```php
$cc = new ChangeCase;
$cc->titleCase('a simple test');
// 'A Simple Test'
```

## License
The MIT License (MIT). Please see [License File](/LICENSE) for more information.
