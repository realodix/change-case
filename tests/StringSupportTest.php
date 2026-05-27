<?php

namespace Realodix\ChangeCase\Test;

use PHPUnit\Framework\TestCase;
use Realodix\ChangeCase\Support\Str;

class StringSupportTest extends TestCase
{
    public function testUcsplitMethod()
    {
        $this->assertSame(['Foo', 'Bar'], Str::ucsplit('FooBar'));
        $this->assertSame(['Foo_Bar'], Str::ucsplit('Foo_Bar'));
        $this->assertSame(['Foo_B_a_r_baz'], Str::ucsplit('Foo_B_a_r_baz'));
        $this->assertSame(['foo', 'BAR', 'Baz'], Str::ucsplit('fooBARBaz'));
        $this->assertSame(['Foo-ba', 'R-baz'], Str::ucsplit('Foo-baR-baz'));
    }
}
