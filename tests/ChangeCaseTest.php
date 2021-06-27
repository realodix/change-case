<?php

namespace Realodix\ChangeCase\Test;

use PHPUnit\Framework\TestCase;
use Realodix\ChangeCase\ChangeCase;

class ChangeCaseTest extends TestCase
{
    use ChangeCaseTestProvider;

    private $cc;

    protected function setUp(): void
    {
        $this->cc = new ChangeCase;
    }

    /**
     * @test
     * @dataProvider noCaseProvider
     *
     * @param mixed $actual
     * @param mixed $expected
     */
    public function no($actual, $expected)
    {
        $this->assertSame($expected, $this->cc->no($actual));
    }

    /** @test */
    public function noCaseWithOpt()
    {
        $options = ['delimiter' => '#'];
        $this->assertSame('camel#case', $this->cc->no('camelCase', $options));
        $this->assertSame('#camel#case#', $this->cc->no('#camel Case#', $options));

        // Custom splitRegexp
        $this->assertSame(
            'minify urls',
            $this->cc->no('minifyURLs', ['splitRegexp' => '/([a-z])([A-Z0-9])/'])
        );

        // Separate Numbers
        $options = ['separateNumber' => true];
        $this->assertSame('test string 123', $this->cc->no('testString123', $options));
        $this->assertSame('foo 123 bar', $this->cc->no('Foo123Bar', $options));
        $this->assertSame('a number 2 in', $this->cc->no('aNumber2in', $options));
        $this->assertSame('v1 test', $this->cc->no('V1Test'));
        $this->assertSame(
            'v 1 test with separate number',
            $this->cc->no('V1Test with separateNumber', $options)
        );
    }

    /**
     * @test
     * @dataProvider camelCaseProvider
     *
     * @param mixed $expected
     * @param mixed $actual
     */
    public function camel($expected, $actual)
    {
        $this->assertSame($expected, $this->cc->camel($actual));
    }

    /** @test */
    public function camelCaseWithOpt()
    {
        // Separate Numbers
        $this->assertSame('1TwoThree', $this->cc->camel('1twoThree', ['separateNumber' => true]));

        // https://github.com/blakeembrey/change-case/issues/216
        $this->assertSame('helloWorld', $this->cc->camel('hello__world', ['splitRegexp' => '/(__)/']));
    }

    /**
     * @test
     * @dataProvider capitalCaseProvider
     *
     * @param mixed $expected
     * @param mixed $actual
     */
    public function capital($expected, $actual)
    {
        $this->assertSame($expected, $this->cc->capital($actual));
    }

    /**
     * @test
     * @dataProvider constantCaseProvider
     *
     * @param mixed $expected
     * @param mixed $actual
     */
    public function constant($expected, $actual)
    {
        $this->assertSame($expected, $this->cc->constant($actual));
    }

    /**
     * @test
     * @dataProvider dotCaseProvider
     *
     * @param mixed $expected
     * @param mixed $actual
     */
    public function dot($expected, $actual)
    {
        $this->assertSame($expected, $this->cc->dot($actual));
    }

    /** @test */
    public function dotCaseWithOpt()
    {
        $this->assertSame('f.0.obar', $this->cc->dot('f0obar', ['separateNumber' => true]));
    }

    /**
     * @test
     * @dataProvider headerCaseProvider
     *
     * @param mixed $expected
     * @param mixed $actual
     */
    public function header($expected, $actual)
    {
        $this->assertSame($expected, $this->cc->header($actual));
    }

    /**
     * @test
     * @dataProvider pascalCaseProvider
     *
     * @param mixed $expected
     * @param mixed $actual
     */
    public function pascal($expected, $actual)
    {
        $this->assertSame($expected, $this->cc->pascal($actual));
    }

    /**
     * @test
     * @dataProvider pathCaseProvider
     *
     * @param mixed $expected
     * @param mixed $actual
     */
    public function path($expected, $actual)
    {
        $this->assertSame($expected, $this->cc->path($actual));
    }

    /**
     * @test
     * @dataProvider sentenceCaseProvider
     *
     * @param mixed $expected
     * @param mixed $actual
     */
    public function sentence($expected, $actual)
    {
        $this->assertSame($expected, $this->cc->sentence($actual));
    }

    /**
     * @test
     * @dataProvider snakeCaseProvider
     *
     * @param mixed $expected
     * @param mixed $actual
     */
    public function snake($expected, $actual)
    {
        $this->assertSame($expected, $this->cc->snake($actual));
    }

    /** @test */
    public function snakeCaseWithOpt()
    {
        $options = ['separateNumber' => true];

        $this->assertSame(
            'test_v_2',
            $this->cc->snake('TestV2', $options)
        );
        $this->assertSame(
            'foo_123_bar',
            $this->cc->snake('Foo123Bar', $options)
        );
    }

    /**
     * @test
     * @dataProvider spinalCaseProvider
     *
     * @param mixed $expected
     * @param mixed $actual
     */
    public function spinal($expected, $actual)
    {
        $this->assertSame($expected, $this->cc->spinal($actual));
    }

    /** @test */
    public function spinalCaseWithOpt()
    {
        $options = ['separateNumber' => true];

        $this->assertSame(
            'version-v-1-2-10',
            $this->cc->spinal('version v1.2.10', $options)
        );
        $this->assertSame(
            'foo-123-bar',
            $this->cc->spinal('Foo123Bar', $options)
        );
    }

    /**
     * @test
     * @dataProvider swapCaseProvider
     *
     * @param mixed $expected
     * @param mixed $actual
     */
    public function swap($expected, $actual)
    {
        $this->assertSame($expected, $this->cc->swap($actual));
    }

    /**
     * @test
     * @dataProvider titleCaseProvider
     *
     * @param mixed $actual
     * @param mixed $expected
     */
    public function title($actual, $expected)
    {
        $this->assertSame($expected, $this->cc->title($actual));
    }

    /** @test */
    public function titleCaseWithIgnore()
    {
        $this->assertSame(
            'Do re Mi',
            $this->cc->title('do re mi', ['re'])
        );
    }

    /** @test */
    public function unicode()
    {
        // Mark
        $this->assertSame('ââ êê', $this->cc->no('"ÂÂ ÊÊ"'));
        $this->assertSame('ââ êê', $this->cc->no('ÂÂ ÊÊ'));
        $this->assertSame('ââÊê', $this->cc->camel('ÂÂ êê'));
        $this->assertSame('ââÊê', $this->cc->camel('ââ ÊÊ'));
        $this->assertSame('Ââ Êê', $this->cc->capital('ÂÂ ÊÊ'));
        $this->assertSame('ÂÂ_ÊÊ', $this->cc->constant('ÂÂ ÊÊ'));
        $this->assertSame('Ââ-Êê', $this->cc->header('ÂÂ ÊÊ'));
        $this->assertSame('ââ/êê', $this->cc->path('ÂÂ ÊÊ'));
        $this->assertSame('Ââ êê', $this->cc->sentence('ÂÂ ÊÊ'));
        $this->assertSame('âÂ õÕ', $this->cc->swap('Ââ Õõ'));

        // Mark + Number
        $this->assertSame('ââ ⅰⅰ', $this->cc->no('"ÂÂ ⅠⅠ"'));
        $this->assertSame('ââ ⅰⅰ', $this->cc->no('ÂÂ ⅠⅠ'));
        $this->assertSame('Ââ Ⅰⅰ', $this->cc->capital('ÂÂ ⅠⅠ'));
        $this->assertSame('ÂÂ_ⅠⅠ', $this->cc->constant('ÂÂ ⅠⅠ'));
        $this->assertSame('Ââ-Ⅰⅰ', $this->cc->header('ÂÂ ⅠⅠ'));
        $this->assertSame('ââ/ⅰⅰ', $this->cc->path('ÂÂ ⅠⅠ'));
        $this->assertSame('Ââ ⅰⅰ', $this->cc->sentence('ÂÂ ⅠⅠ'));
        $this->assertSame('âÂ õÕ', $this->cc->swap('Ââ Õõ'));
    }
}
