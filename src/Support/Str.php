<?php

namespace Realodix\ChangeCase\Support;

/**
 * @internal
 */
class Str
{
    /**
     * Split a string into pieces by uppercase characters.
     *
     * Example:
     * - "FooBar" => ["Foo", "Bar"]
     * - "Foo_Bar" => ["Foo_", "Bar"]
     * - "Foo_B_a_r_baz" => ["Foo_", "B_a_r_baz"]
     * - "fooBARBaz" => ["foo", "B", "A", "R", "Baz"]
     * - "Foo-baR-baz" => ["Foo-ba", "R-baz"]
     *
     * @return array<string>
     *
     * @throws \RuntimeException Regex failed, e.g. because of invalid UTF-8 in the string
     */
    public static function ucsplit(string $string)
    {
        $substrings = \preg_split('/(?=\p{Lu})/u', $string, -1, PREG_SPLIT_NO_EMPTY);

        if ($substrings === false) {
            throw new \RuntimeException('Error while splitting string');
        }

        return $substrings;
    }
}
