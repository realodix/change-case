# Change Case

![PHPVersion](https://img.shields.io/badge/PHP-^7.4|^8-777BB4.svg?style=flat-square)
![Tests](https://github.com/realodix/change-case/actions/workflows/tests.yml/badge.svg)
[![GitHub license](https://img.shields.io/github/license/realodix/change-case)](/LICENSE)

> Transform a string between `camelCase`, `PascalCase`, `Capital Case`, `snake_case`, `param-case`, `CONSTANT_CASE` and others.

## Installation

You can install the package via composer:

```sh
composer require realodix/change-case
```

## Usage

```php
use Realodix\ChangeCase\ChangeCase;
```

### Methods Available

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
- [`swapCase`](#swapcase)
- [`titleCase`](#titlecase)

### Options

- `delimiter`: (string) Used between words
- `splitRegexp`: (RegExp) Used to split into word segments
- `splitNumberRegexp`: (RegExp) Used to split numbers
- `stripRegexp`: (RegExp) Used to remove extraneous characters
- `separateNumber`: (bool) Used to separate numbers or not

#### camelCase

> Transform into a string with the separator denoted by the next word capitalized.

💡 Support [options](#options)

```php
$cc = new ChangeCase;

$cc->camel('test string');
// 'testString'

$cc->camel('1twoThree');
// '1twoThree'
$cc->camel('1twoThree', ['separateNumber' => true]);
// '1TwoThree'
```

#### Capital Case

> Transform into a space separated string with each word capitalized.

```php
$cc = new ChangeCase;
$cc->capital('test string');
// 'Test String'
```

#### CONSTANT_CASE

> Transform into upper case string with an underscore between words.

```php
$cc = new ChangeCase;
$cc->constant('test string');
// 'TEST_STRING'
```

#### dot.case

> Transform into a lower case string with a period between words.

💡 Support [options](#options)

```php
$cc = new ChangeCase;
$cc->dot('test string');
// 'test.string'
```

#### Header-Case

> Transform into a dash separated string of capitalized words.

```php
$cc = new ChangeCase;
$cc->header('test string');
// 'Test-String'
```

#### no case

> Transform into a lower cased string with spaces between words.

💡 Support [options](#options)

```php
$cc = new ChangeCase;

$cc->no('testString');
// 'test string'

$cc->no('minifyURLs', ['delimiter' => '-']);
// 'minify-urls'

$cc->no('Foo123Bar')
// foo123 bar
$cc->no('Foo123Bar', ['separateNumber' => true])
// foo 123 bar
```

#### PascalCase

> Transform into a string of capitalized words without separators.

💡 Support [options](#options)

```php
$cc = new ChangeCase;
$cc->pascal('test string');
// 'TestString'
```

#### path/case

> Transform into a lower case string with slashes between words.

💡 Support [options](#options)

```php
$cc = new ChangeCase;
$cc->path('test string');
// 'test/string'
```

#### Sentence case

> Transform into a lower case with spaces between words, then capitalize the string.

```php
$cc = new ChangeCase;
$cc->sentence('testString');
// 'Test string'
```

#### snake_case

> Transform into a lower case string with underscores between words.

💡 Support [options](#options)

```php
$cc = new ChangeCase;

$cc->snake('test string');
// 'test_string'

$cc->snake('Foo123Bar');
// 'foo123_bar'
$cc->snake('Foo123Bar', ['separateNumber' => true]);
// 'foo_123_bar'
```

#### spinal-case

> Transform into a lower cased string with dashes between words.

💡 Support [options](#options)

```php
$cc = new ChangeCase;

$cc->spinal('test string');
// 'test-string'

$cc->spinal('Foo123Bar');
// 'foo123-bar'
$cc->spinal('Foo123Bar', ['separateNumber' => true]);
// 'foo-123-bar'
```


#### swapCase

> Transform a string by swapping every character from upper to lower case, or lower to upper case.

```php
$cc = new ChangeCase;
$cc->swap('Test String');
// 'tEST sTRING'
```

#### titleCase

> Transform a string into title case following English rules.

`title(string $string, array $ignore = [])`

```php
$cc = new ChangeCase;
$cc->title('a simple test');
// 'A Simple Test'
```

## License
The MIT License (MIT). Please see [License File](/LICENSE) for more information.
