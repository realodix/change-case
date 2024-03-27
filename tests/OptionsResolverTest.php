<?php

namespace Realodix\ChangeCase\Test;

use PHPUnit\Framework\TestCase;
use Realodix\ChangeCase\ChangeCase;

class OptionsResolverTest extends TestCase
{
    /**
     * Options with wrong parameters.
     */
    public function testWrongOptionsParameter()
    {
        $this->expectException(\Symfony\Component\OptionsResolver\Exception\UndefinedOptionsException::class);
        $this->expectExceptionMessage('The option "foo" does not exist');

        ChangeCase::camel('Foo123Bar', ['foo' => 'bar']);
    }

    /**
     * Options with wrong parameters value type.
     */
    public function testWrongOptionsParameterValueType()
    {
        $this->expectException(\Symfony\Component\OptionsResolver\Exception\InvalidOptionsException::class);
        $this->expectExceptionMessage('The option "separateNum" with value "foo" is expected to be of type "bool"');

        ChangeCase::camel('Foo123Bar', ['separateNum' => 'foo']);
    }
}
