<?php

namespace Realodix\ChangeCase\Test;

use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;
use Realodix\ChangeCase\ChangeCase;

class ChangeCaseTest extends TestCase
{
    use ChangeCaseTestProvider;

    #[DataProvider('noCaseProvider')]
    public function testNoCaseMothod($expected, $actual)
    {
        $this->assertSame($expected, ChangeCase::no($actual));
    }

    public function testNoCaseMothodWithOpt()
    {
        $options = ['delimiter' => '#'];
        $this->assertSame('camel#case', ChangeCase::no('camelCase', $options));
        $this->assertSame('#camel#case#', ChangeCase::no('#camel Case#', $options));

        // Custom splitRegexp
        $this->assertSame(
            'minify urls',
            ChangeCase::no('minifyURLs', ['splitRx' => '/([a-z])([A-Z0-9])/']),
        );

        // Separate Numbers
        $options = ['separateNum' => true];
        $this->assertSame('test string 123', ChangeCase::no('testString123', $options));
        $this->assertSame('foo 123 bar', ChangeCase::no('Foo123Bar', $options));
        $this->assertSame('a number 2 in', ChangeCase::no('aNumber2in', $options));
        $this->assertSame('v1 test', ChangeCase::no('V1Test'));
        $this->assertSame(
            'v 1 test with separate number',
            ChangeCase::no('V1Test with separateNumber', $options),
        );
    }

    #[DataProvider('camelCaseProvider')]
    public function testCamelCaseMethod($expected, $actual)
    {
        $this->assertSame($expected, ChangeCase::camel($actual));
    }

    public function testCamelCaseMethodWithOpt()
    {
        // Separate Numbers
        $this->assertSame('1TwoThree', ChangeCase::camel('1twoThree', ['separateNum' => true]));

        // https://github.com/blakeembrey/change-case/issues/216
        $this->assertSame('helloWorld', ChangeCase::camel('hello__world', ['splitRx' => '/(__)/']));
    }

    #[DataProvider('constantCaseProvider')]
    public function testConstantCaseMethod($expected, $actual)
    {
        $this->assertSame($expected, ChangeCase::constant($actual));
    }

    #[DataProvider('dotCaseProvider')]
    public function testDotCaseMethod($expected, $actual)
    {
        $this->assertSame($expected, ChangeCase::dot($actual));
    }

    public function testDotCaseMethodWithOpt()
    {
        $this->assertSame('f.0.obar', ChangeCase::dot('f0obar', ['separateNum' => true]));
        // Change default options
        $this->assertSame('camel:case', ChangeCase::dot('camelCase', ['delimiter' => ':']));
    }

    #[DataProvider('headerCaseProvider')]
    public function testHeaderCaseMethod($expected, $actual)
    {
        $this->assertSame($expected, ChangeCase::header($actual));
    }

    public function testHeaderCaseMethodWithOpt()
    {
        $options = ['separateNum' => true];

        $this->assertSame(
            'Test-V-2',
            ChangeCase::header('TestV2', $options),
        );
        $this->assertSame(
            'Foo-123-Bar',
            ChangeCase::header('Foo123Bar', $options),
        );

        // Change default options
        $this->assertSame('Foo:bar', ChangeCase::header('FooBar', ['delimiter' => ':']));
    }

    #[DataProvider('headlineCaseProvider')]
    public function testHeadlineCaseMethod($expected, $actual)
    {
        $this->assertSame($expected, ChangeCase::headline($actual));
    }

    #[DataProvider('kebabCaseProvider')]
    public function testKebabCaseMethod($expected, $actual)
    {
        $this->assertSame($expected, ChangeCase::kebab($actual));
    }

    public function testKebabCaseMethodWithOpt()
    {
        $options = ['separateNum' => true];

        $this->assertSame(
            'version-v-1-2-10',
            ChangeCase::kebab('version v1.2.10', $options),
        );
        $this->assertSame(
            'foo-123-bar',
            ChangeCase::kebab('Foo123Bar', $options),
        );

        // Change default options
        $this->assertSame('foo:bar', ChangeCase::kebab('FooBar', ['delimiter' => ':']));
    }

    #[DataProvider('pascalCaseProvider')]
    public function testPascalCaseMethod($expected, $actual)
    {
        $this->assertSame($expected, ChangeCase::pascal($actual));
    }

    #[DataProvider('pathCaseProvider')]
    public function testPathCaseMethod($expected, $actual)
    {
        $this->assertSame($expected, ChangeCase::path($actual));
    }

    public function testPathCaseMethodWithOpt()
    {
        $options = ['separateNum' => true];

        $this->assertSame(
            'test/v/2',
            ChangeCase::path('TestV2', $options),
        );
        $this->assertSame(
            'foo/123/bar',
            ChangeCase::path('Foo123Bar', $options),
        );

        // Change default options
        $this->assertSame('foo:bar', ChangeCase::path('FooBar', ['delimiter' => ':']));
    }

    #[DataProvider('sentenceCaseProvider')]
    public function testSentenceCaseMethod($expected, $actual)
    {
        $this->assertSame($expected, ChangeCase::sentence($actual));
    }

    #[DataProvider('snakeCaseProvider')]
    public function testSnakeCaseMethod($expected, $actual)
    {
        $this->assertSame($expected, ChangeCase::snake($actual));
    }

    public function testSnakeCaseMethodWithOpt()
    {
        $options = ['separateNum' => true];

        $this->assertSame(
            'test_v_2',
            ChangeCase::snake('TestV2', $options),
        );
        $this->assertSame(
            'foo_123_bar',
            ChangeCase::snake('Foo123Bar', $options),
        );

        // Change default options
        $this->assertSame('foo:bar', ChangeCase::snake('FooBar', ['delimiter' => ':']));
    }

    #[DataProvider('swapCaseProvider')]
    public function testSwapCaseMethod($expected, $actual)
    {
        $this->assertSame($expected, ChangeCase::swap($actual));
    }

    public function testApostrophe()
    {
        $options = ['apostrophe' => true];

        $this->assertSame("assistant's name", ChangeCase::no("Assistant's name", $options));
        $this->assertSame("assistant'sName", ChangeCase::camel("Assistant's name", $options));
        $this->assertSame("assistant's.name", ChangeCase::dot("Assistant's name", $options));
        $this->assertSame("Assistant's-Name", ChangeCase::header("Assistant's name", $options));
        $this->assertSame("assistant's-name", ChangeCase::kebab("Assistant's name", $options));
        $this->assertSame("Assistant'sName", ChangeCase::pascal("Assistant's name", $options));
        $this->assertSame("assistant's/name", ChangeCase::path("Assistant's name", $options));
        $this->assertSame("assistant's_name", ChangeCase::snake("Assistant's name", $options));
    }

    public function testUnicode()
    {
        // Mark
        $this->assertSame('ââ êê', ChangeCase::no('"ÂÂ ÊÊ"'));
        $this->assertSame('ââ êê', ChangeCase::no('ÂÂ ÊÊ'));
        $this->assertSame('ââÊê', ChangeCase::camel('ÂÂ êê'));
        $this->assertSame('ââÊê', ChangeCase::camel('ââ ÊÊ'));
        $this->assertSame('ÂÂ_ÊÊ', ChangeCase::constant('ÂÂ ÊÊ'));
        $this->assertSame('Ââ-Êê', ChangeCase::header('ÂÂ ÊÊ'));
        $this->assertSame('ââ/êê', ChangeCase::path('ÂÂ ÊÊ'));
        $this->assertSame('Ââ êê', ChangeCase::sentence('ÂÂ ÊÊ'));
        $this->assertSame('âÂ õÕ', ChangeCase::swap('Ââ Õõ'));

        // Mark + Number
        $this->assertSame('ââ ⅰⅰ', ChangeCase::no('"ÂÂ ⅠⅠ"'));
        $this->assertSame('ââ ⅰⅰ', ChangeCase::no('ÂÂ ⅠⅠ'));
        $this->assertSame('ÂÂ_ⅠⅠ', ChangeCase::constant('ÂÂ ⅠⅠ'));
        $this->assertSame('Ââ-Ⅰⅰ', ChangeCase::header('ÂÂ ⅠⅠ'));
        $this->assertSame('ââ/ⅰⅰ', ChangeCase::path('ÂÂ ⅠⅠ'));
        $this->assertSame('Ââ ⅰⅰ', ChangeCase::sentence('ÂÂ ⅠⅠ'));
        $this->assertSame('âÂ õÕ', ChangeCase::swap('Ââ Õõ'));
    }
}
