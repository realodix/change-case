<?php

namespace Realodix\ChangeCase\Test;

use PHPUnit\Framework\TestCase;
use Realodix\ChangeCase\Support\Str;

class StringSupportTest extends TestCase
{
    /** @test */
    public function ucsplit()
    {
        $this->assertSame(['Foo', 'Bar'], Str::ucsplit('FooBar'));
        $this->assertSame(['Foo_', 'Bar'], Str::ucsplit('Foo_Bar'));
        $this->assertSame(['Foo_', 'B_a_r_baz'], Str::ucsplit('Foo_B_a_r_baz'));
        $this->assertSame(['foo', 'B', 'A', 'R', 'Baz'], Str::ucsplit('fooBARBaz'));
        $this->assertSame(['Foo-ba', 'R-baz'], Str::ucsplit('Foo-baR-baz'));
    }

    /** @test */
    public function str_slice()
    {
        $str = 'The quick brown fox jumps over the lazy dog.';

        $this->assertSame('the lazy dog.', Str::str_slice($str, 31));
        $this->assertSame('quick brown fox', Str::str_slice($str, 4, 19));
        $this->assertSame('dog.', Str::str_slice($str, -4));
        $this->assertSame('lazy', Str::str_slice($str, -9, -5));
    }
}
