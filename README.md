# Change Case

![PHPVersion](https://img.shields.io/badge/PHP-%208.1-777BB4.svg?style=flat-square)
![Tests](https://github.com/realodix/change-case/actions/workflows/tests.yml/badge.svg)
[![GitHub license](https://img.shields.io/github/license/realodix/change-case)](/LICENSE)

> Transform a string between `camelCase`, `PascalCase`, `Headline Case`, `snake_case`, `param-case`, `CONSTANT_CASE` and others.

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
- [`CONSTANT_CASE`](#constant_case)
- [`dot.case`](#dotcase)
- [`Header-Case`](#header-case)
- [`Headline Case`](#headline-case)
- [`kebab-case`](#kebab-case)
- [`no case`](#no-case)
- [`PascalCase`](#pascalcase)
- [`path/case`](#pathcase)
- [`Sentence case`](#sentence-case)
- [`snake_case`](#snake_case)
- [`swapCase`](#swapcase)
- [`Title Case`](#titlecase)

### Options

Every method that gets 💡 flag, they can support option

- `delimiter`: (string) This character separates each chunk of data within the text string. Default: singgle space.
- `splitRx`: (RegExp) Used to split into word segments.
- `stripRx`: (RegExp) Used to remove extraneous characters.
- `separateNum`: (bool) Used to separate numbers or not. Default: false.
- `apostrophe`: (bool) Used to separate apostrophe or not. Default: false.

Examples
```php
ChangeCase::camel('1twoThree', ['separateNum' => true]);
// '1TwoThree'
```

#### camelCase
> Transform into a string with the separator denoted by the next word capitalized.

💡 Support [options](#options)

```php
ChangeCase::camel('test string');
// 'testString'

ChangeCase::camel('1twoThree');
// '1twoThree'
ChangeCase::camel('1twoThree', ['separateNum' => true]);
// '1TwoThree'
```

#### CONSTANT_CASE
> Transform into upper case string with an underscore between words.

```php
ChangeCase::constant('test string');
// 'TEST_STRING'
```

#### dot.case
> Transform into a lower case string with a period between words.

💡 Support [options](#options)

```php
ChangeCase::dot('test string');
// 'test.string'
```

#### Header-Case
> Transform into a dash separated string of capitalized words.

💡 Support [options](#options)

```php
ChangeCase::header('test string');
// 'Test-String'
```

#### Headline Case
> Transform a strings delimited by casing, hyphens, or underscores into a space delimited string with each word's first letter capitalized.

```php
ChangeCase::headline('test string');
// 'Test String'
ChangeCase::headline('steve_jobs');
// Steve Jobs
ChangeCase::headline('EmailNotificationSent');
// Email Notification Sent
```

The difference with the [`title`](#titlecase):

```php
ChangeCase::headline('php_v8.3'); // 'Php V8.3'
ChangeCase::title('php_v8.3');    // 'Php_V8.3'

ChangeCase::headline('phpV8.3'); // 'Php V8.3'
ChangeCase::title('phpV8.3');    // 'Phpv8.3'

ChangeCase::headline('_foo_'); // 'Foo'
ChangeCase::title('_foo_');    // '_Foo_'
```

#### kebab-case
> Transform into a lower cased string with dashes between words.

💡 Support [options](#options)

```php
ChangeCase::kebab('test string');
// 'test-string'

ChangeCase::kebab('Foo123Bar');
// 'foo123-bar'
ChangeCase::kebab('Foo123Bar', ['separateNum' => true]);
// 'foo-123-bar'
```

#### no case
> Transform into a lower cased string with spaces between words, and clean up the string from non-word characters.

💡 Support [options](#options)

```php
ChangeCase::no('testString');
// 'test string'

ChangeCase::no('Foo123Bar')
// foo123 bar
ChangeCase::no('Foo123Bar', ['separateNum' => true])
// foo 123 bar
```

#### PascalCase
> Transform into a string of capitalized words without separators.

💡 Support [options](#options)

```php
ChangeCase::pascal('test string');
// 'TestString'
```

#### path/case
> Transform into a lower case string with slashes between words.

💡 Support [options](#options)

```php
ChangeCase::path('test string');
// 'test/string'
```

#### Sentence case
> Transform into a lower case with spaces between words, then capitalize the string.

💡 Support [options](#options)

```php
ChangeCase::sentence('testString');
// 'Test string'
```

#### snake_case
> Transform into a lower case string with underscores between words.

💡 Support [options](#options)

```php
ChangeCase::snake('test string');
// 'test_string'

ChangeCase::snake('Foo123Bar');
// 'foo123_bar'
ChangeCase::snake('Foo123Bar', ['separateNum' => true]);
// 'foo_123_bar'
```

#### swapCase
> Transform a string by swapping every character from upper to lower case, or lower to upper case.

```php
ChangeCase::swap('Test String');
// 'tEST sTRING'
```

#### titleCase
> Transform the given string to `Title Case`:

```php
ChangeCase::title('a simple test');
// 'A Simple Test'
```

The difference with the [`headline`](#head-line-case):

```php
ChangeCase::headline('php_v8.3'); // 'Php V8.3'
ChangeCase::title('php_v8.3');    // 'Php_V8.3'

ChangeCase::headline('phpV8.3'); // 'Php V8.3'
ChangeCase::title('phpV8.3');    // 'Phpv8.3'

ChangeCase::headline('_foo_'); // 'Foo'
ChangeCase::title('_foo_');    // '_Foo_'
```

## License
The MIT License (MIT). Please see [License File](/LICENSE) for more information.
