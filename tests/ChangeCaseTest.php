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
        $this->assertSame($expected, (new ChangeCase)->noCase($actual));
    }

    /** @test */
    public function noCaseWithOpt()
    {
        $this->assertSame('camel#case', $this->cc->noCase('camelCase', '#'));
        $this->assertSame('#camel#case#', $this->cc->noCase('#camel Case#', '#'));

        // Custom splitRegexp
        $this->assertSame(
            'minify urls',
            $this->cc->noCase('minifyURLs', splitRegexp: '/([a-z])([A-Z0-9])/')
        );

        // Separate Numbers
        $this->assertSame('test string 123', $this->cc->noCase('testString123', separateNumbers: true));
        $this->assertSame('foo 123 bar', $this->cc->noCase('Foo123Bar', separateNumbers: true));
        $this->assertSame('a number 2 in', $this->cc->noCase('aNumber2in', separateNumbers: true));
        $this->assertSame('v1 test', $this->cc->noCase('V1Test'));
        $this->assertSame(
            'v 1 test with separate numbers',
            $this->cc->noCase('V1Test with separateNumbers', separateNumbers: true)
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
        $this->assertSame($expected, (new ChangeCase)->camelCase($actual));
    }

    /** @test */
    public function camelCaseWithOpt()
    {
        // Separate Numbers
        $this->assertSame('1TwoThree', $this->cc->camelCase('1twoThree', separateNumbers: true));
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
        $this->assertSame($expected, (new ChangeCase)->capitalCase($actual));
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
        $this->assertSame($expected, (new ChangeCase)->constantCase($actual));
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
        $this->assertSame($expected, (new ChangeCase)->dotCase($actual));
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
        $this->assertSame($expected, (new ChangeCase)->headerCase($actual));
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
        $this->assertSame($expected, (new ChangeCase)->pascalCase($actual));
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
        $this->assertSame($expected, (new ChangeCase)->pathCase($actual));
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
        $this->assertSame($expected, (new ChangeCase)->sentenceCase($actual));
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
        $this->assertSame($expected, (new ChangeCase)->snakeCase($actual));
    }

    /** @test */
    public function snakeCaseWithOpt()
    {
        $this->assertSame(
            'test_v_2',
            $this->cc->snakeCase('TestV2', separateNumbers: true)
        );
        $this->assertSame(
            'foo_123_bar',
            $this->cc->snakeCase('Foo123Bar', separateNumbers: true)
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
        $this->assertSame($expected, (new ChangeCase)->spinalCase($actual));
    }

    /** @test */
    public function spinalCaseWithOpt()
    {
        $this->assertSame(
            'version-v-1-2-10',
            $this->cc->spinalCase('version v1.2.10', separateNumbers: true)
        );
        $this->assertSame(
            'foo-123-bar',
            $this->cc->spinalCase('Foo123Bar', separateNumbers: true)
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
        $this->assertSame($expected, (new ChangeCase)->swapCase($actual));
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
        $this->assertSame($expected, (new ChangeCase)->titleCase($actual));
    }
}
