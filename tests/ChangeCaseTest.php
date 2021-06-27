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
    public function noCase($actual, $expected)
    {
        $this->assertSame($expected, $this->cc->noCase($actual));
    }

    /** @test */
    public function noCaseWithOpt()
    {
        $options = ['delimiter' => '#'];
        $this->assertSame('camel#case', $this->cc->noCase('camelCase', $options));
        $this->assertSame('#camel#case#', $this->cc->noCase('#camel Case#', $options));

        // Custom splitRegexp
        $this->assertSame(
            'minify urls',
            $this->cc->noCase('minifyURLs', ['splitRegexp' => '/([a-z])([A-Z0-9])/'])
        );

        // Separate Numbers
        $options = ['separateNumber' => true];
        $this->assertSame('test string 123', $this->cc->noCase('testString123', $options));
        $this->assertSame('foo 123 bar', $this->cc->noCase('Foo123Bar', $options));
        $this->assertSame('a number 2 in', $this->cc->noCase('aNumber2in', $options));
        $this->assertSame('v1 test', $this->cc->noCase('V1Test'));
        $this->assertSame(
            'v 1 test with separate number',
            $this->cc->noCase('V1Test with separateNumber', $options)
        );
    }

    /**
     * @test
     * @dataProvider camelCaseProvider
     *
     * @param mixed $expected
     * @param mixed $actual
     */
    public function camelCase($expected, $actual)
    {
        $this->assertSame($expected, $this->cc->camelCase($actual));
    }

    /** @test */
    public function camelCaseWithOpt()
    {
        // Separate Numbers
        $this->assertSame('1TwoThree', $this->cc->camelCase('1twoThree', ['separateNumber' => true]));

        // https://github.com/blakeembrey/change-case/issues/216
        $this->assertSame('helloWorld', $this->cc->camelCase('hello__world', ['splitRegexp' => '/(__)/']));
    }

    /**
     * @test
     * @dataProvider capitalCaseProvider
     *
     * @param mixed $expected
     * @param mixed $actual
     */
    public function capitalCase($expected, $actual)
    {
        $this->assertSame($expected, $this->cc->capitalCase($actual));
    }

    /**
     * @test
     * @dataProvider constantCaseProvider
     *
     * @param mixed $expected
     * @param mixed $actual
     */
    public function constantCase($expected, $actual)
    {
        $this->assertSame($expected, $this->cc->constantCase($actual));
    }

    /**
     * @test
     * @dataProvider dotCaseProvider
     *
     * @param mixed $expected
     * @param mixed $actual
     */
    public function dotCase($expected, $actual)
    {
        $this->assertSame($expected, $this->cc->dotCase($actual));
    }

    /** @test */
    public function dotCaseWithOpt()
    {
        $this->assertSame('f.0.obar', $this->cc->dotCase('f0obar', ['separateNumber' => true]));
    }

    /**
     * @test
     * @dataProvider headerCaseProvider
     *
     * @param mixed $expected
     * @param mixed $actual
     */
    public function headerCase($expected, $actual)
    {
        $this->assertSame($expected, $this->cc->headerCase($actual));
    }

    /**
     * @test
     * @dataProvider pascalCaseProvider
     *
     * @param mixed $expected
     * @param mixed $actual
     */
    public function pascalCase($expected, $actual)
    {
        $this->assertSame($expected, $this->cc->pascalCase($actual));
    }

    /**
     * @test
     * @dataProvider pathCaseProvider
     *
     * @param mixed $expected
     * @param mixed $actual
     */
    public function pathCase($expected, $actual)
    {
        $this->assertSame($expected, $this->cc->pathCase($actual));
    }

    /**
     * @test
     * @dataProvider sentenceCaseProvider
     *
     * @param mixed $expected
     * @param mixed $actual
     */
    public function sentenceCase($expected, $actual)
    {
        $this->assertSame($expected, $this->cc->sentenceCase($actual));
    }

    /**
     * @test
     * @dataProvider snakeCaseProvider
     *
     * @param mixed $expected
     * @param mixed $actual
     */
    public function snakeCase($expected, $actual)
    {
        $this->assertSame($expected, $this->cc->snakeCase($actual));
    }

    /** @test */
    public function snakeCaseWithOpt()
    {
        $options = ['separateNumber' => true];

        $this->assertSame(
            'test_v_2',
            $this->cc->snakeCase('TestV2', $options)
        );
        $this->assertSame(
            'foo_123_bar',
            $this->cc->snakeCase('Foo123Bar', $options)
        );
    }

    /**
     * @test
     * @dataProvider spinalCaseProvider
     *
     * @param mixed $expected
     * @param mixed $actual
     */
    public function spinalCase($expected, $actual)
    {
        $this->assertSame($expected, $this->cc->spinalCase($actual));
    }

    /** @test */
    public function spinalCaseWithOpt()
    {
        $options = ['separateNumber' => true];

        $this->assertSame(
            'version-v-1-2-10',
            $this->cc->spinalCase('version v1.2.10', $options)
        );
        $this->assertSame(
            'foo-123-bar',
            $this->cc->spinalCase('Foo123Bar', $options)
        );
    }

    /**
     * @test
     * @dataProvider swapCaseProvider
     *
     * @param mixed $expected
     * @param mixed $actual
     */
    public function swapCase($expected, $actual)
    {
        $this->assertSame($expected, $this->cc->swapCase($actual));
    }

    /**
     * @test
     * @dataProvider titleCaseProvider
     *
     * @param mixed $actual
     * @param mixed $expected
     */
    public function titleCase($actual, $expected)
    {
        $this->assertSame($expected, $this->cc->titleCase($actual));
    }

    /** @test */
    public function titleCaseWithIgnore()
    {
        $this->assertSame(
            'Do re Mi',
            $this->cc->titleCase('do re mi', ['re'])
        );
    }
}
