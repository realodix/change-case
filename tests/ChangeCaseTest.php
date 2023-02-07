<?php

namespace Realodix\ChangeCase\Test;

use PHPUnit\Framework\TestCase;
use Realodix\ChangeCase\ChangeCase;

class ChangeCaseTest extends TestCase
{
    use ChangeCaseTestProvider;

    /**
     * @test
     * @dataProvider noCaseProvider
     */
    public function no($expected, $actual)
    {
        $this->assertSame($expected, ChangeCase::no($actual));
    }

    /** @test */
    public function noCaseWithOpt()
    {
        $options = ['delimiter' => '#'];
        $this->assertSame('camel#case', ChangeCase::no('camelCase', $options));
        $this->assertSame('#camel#case#', ChangeCase::no('#camel Case#', $options));

        // Custom splitRegexp
        $this->assertSame(
            'minify urls',
            ChangeCase::no('minifyURLs', ['splitRx' => '/([a-z])([A-Z0-9])/'])
        );

        // Separate Numbers
        $options = ['separateNum' => true];
        $this->assertSame('test string 123', ChangeCase::no('testString123', $options));
        $this->assertSame('foo 123 bar', ChangeCase::no('Foo123Bar', $options));
        $this->assertSame('a number 2 in', ChangeCase::no('aNumber2in', $options));
        $this->assertSame('v1 test', ChangeCase::no('V1Test'));
        $this->assertSame(
            'v 1 test with separate number',
            ChangeCase::no('V1Test with separateNumber', $options)
        );
    }

    /**
     * @test
     * @dataProvider camelCaseProvider
     */
    public function camel($expected, $actual)
    {
        $this->assertSame($expected, ChangeCase::camel($actual));
    }

    /** @test */
    public function camelCaseWithOpt()
    {
        // Separate Numbers
        $this->assertSame('1TwoThree', ChangeCase::camel('1twoThree', ['separateNum' => true]));

        // https://github.com/blakeembrey/change-case/issues/216
        $this->assertSame('helloWorld', ChangeCase::camel('hello__world', ['splitRx' => '/(__)/']));
    }

    /**
     * @test
     * @dataProvider capitalCaseProvider
     */
    public function capital($expected, $actual)
    {
        $this->assertSame($expected, ChangeCase::capital($actual));
    }

    /**
     * @test
     * @dataProvider constantCaseProvider
     */
    public function constant($expected, $actual)
    {
        $this->assertSame($expected, ChangeCase::constant($actual));
    }

    /**
     * @test
     * @dataProvider dotCaseProvider
     */
    public function dot($expected, $actual)
    {
        $this->assertSame($expected, ChangeCase::dot($actual));
    }

    /** @test */
    public function dotCaseWithOpt()
    {
        $this->assertSame('f.0.obar', ChangeCase::dot('f0obar', ['separateNum' => true]));
        // Change default options
        $this->assertSame('camel:case', ChangeCase::dot('camelCase', ['delimiter' => ':']));
    }

    /**
     * @test
     * @dataProvider headerCaseProvider
     */
    public function header($expected, $actual)
    {
        $this->assertSame($expected, ChangeCase::header($actual));
    }

    /** @test */
    public function headerWithOpt()
    {
        $options = ['separateNum' => true];

        $this->assertSame(
            'Test-V-2',
            ChangeCase::header('TestV2', $options)
        );
        $this->assertSame(
            'Foo-123-Bar',
            ChangeCase::header('Foo123Bar', $options)
        );

        // Change default options
        $this->assertSame('Foo:bar', ChangeCase::header('FooBar', ['delimiter' => ':']));
    }

    /**
     * @test
     * @dataProvider headlineCaseProvider
     */
    public function headline($expected, $actual)
    {
        $this->assertSame($expected, ChangeCase::headline($actual));
    }

    /**
     * @test
     * @dataProvider kebabCaseProvider
     */
    public function kebab($expected, $actual)
    {
        $this->assertSame($expected, ChangeCase::kebab($actual));
    }

    /** @test */
    public function kebabCaseWithOpt()
    {
        $options = ['separateNum' => true];

        $this->assertSame(
            'version-v-1-2-10',
            ChangeCase::kebab('version v1.2.10', $options)
        );
        $this->assertSame(
            'foo-123-bar',
            ChangeCase::kebab('Foo123Bar', $options)
        );

        // Change default options
        $this->assertSame('foo:bar', ChangeCase::kebab('FooBar', ['delimiter' => ':']));
    }

    /**
     * @test
     * @dataProvider pascalCaseProvider
     */
    public function pascal($expected, $actual)
    {
        $this->assertSame($expected, ChangeCase::pascal($actual));
    }

    /**
     * @test
     * @dataProvider pathCaseProvider
     */
    public function path($expected, $actual)
    {
        $this->assertSame($expected, ChangeCase::path($actual));
    }

    /** @test */
    public function pathWithOpt()
    {
        $options = ['separateNum' => true];

        $this->assertSame(
            'test/v/2',
            ChangeCase::path('TestV2', $options)
        );
        $this->assertSame(
            'foo/123/bar',
            ChangeCase::path('Foo123Bar', $options)
        );

        // Change default options
        $this->assertSame('foo:bar', ChangeCase::path('FooBar', ['delimiter' => ':']));
    }

    /**
     * @test
     * @dataProvider sentenceCaseProvider
     */
    public function sentence($expected, $actual)
    {
        $this->assertSame($expected, ChangeCase::sentence($actual));
    }

    /**
     * @test
     * @dataProvider snakeCaseProvider
     */
    public function snake($expected, $actual)
    {
        $this->assertSame($expected, ChangeCase::snake($actual));
    }

    /** @test */
    public function snakeCaseWithOpt()
    {
        $options = ['separateNum' => true];

        $this->assertSame(
            'test_v_2',
            ChangeCase::snake('TestV2', $options)
        );
        $this->assertSame(
            'foo_123_bar',
            ChangeCase::snake('Foo123Bar', $options)
        );

        // Change default options
        $this->assertSame('foo:bar', ChangeCase::snake('FooBar', ['delimiter' => ':']));
    }

    /**
     * @test
     * @dataProvider swapCaseProvider
     */
    public function swap($expected, $actual)
    {
        $this->assertSame($expected, ChangeCase::swap($actual));
    }

    /**
     * @test
     * @dataProvider titleCaseProvider
     */
    public function title($expected, $actual)
    {
        $this->assertSame($expected, ChangeCase::title($actual));
    }

    /** @test */
    public function apostrophe()
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

    /** @test */
    public function unicode()
    {
        // Mark
        $this->assertSame('ââ êê', ChangeCase::no('"ÂÂ ÊÊ"'));
        $this->assertSame('ââ êê', ChangeCase::no('ÂÂ ÊÊ'));
        $this->assertSame('ââÊê', ChangeCase::camel('ÂÂ êê'));
        $this->assertSame('ââÊê', ChangeCase::camel('ââ ÊÊ'));
        $this->assertSame('Ââ Êê', ChangeCase::capital('ÂÂ ÊÊ'));
        $this->assertSame('ÂÂ_ÊÊ', ChangeCase::constant('ÂÂ ÊÊ'));
        $this->assertSame('Ââ-Êê', ChangeCase::header('ÂÂ ÊÊ'));
        $this->assertSame('ââ/êê', ChangeCase::path('ÂÂ ÊÊ'));
        $this->assertSame('Ââ êê', ChangeCase::sentence('ÂÂ ÊÊ'));
        $this->assertSame('âÂ õÕ', ChangeCase::swap('Ââ Õõ'));

        // Mark + Number
        $this->assertSame('ââ ⅰⅰ', ChangeCase::no('"ÂÂ ⅠⅠ"'));
        $this->assertSame('ââ ⅰⅰ', ChangeCase::no('ÂÂ ⅠⅠ'));
        $this->assertSame('Ââ Ⅰⅰ', ChangeCase::capital('ÂÂ ⅠⅠ'));
        $this->assertSame('ÂÂ_ⅠⅠ', ChangeCase::constant('ÂÂ ⅠⅠ'));
        $this->assertSame('Ââ-Ⅰⅰ', ChangeCase::header('ÂÂ ⅠⅠ'));
        $this->assertSame('ââ/ⅰⅰ', ChangeCase::path('ÂÂ ⅠⅠ'));
        $this->assertSame('Ââ ⅰⅰ', ChangeCase::sentence('ÂÂ ⅠⅠ'));
        $this->assertSame('âÂ õÕ', ChangeCase::swap('Ââ Õõ'));
    }
}
