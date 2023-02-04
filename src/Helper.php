<?php

namespace Realodix\ChangeCase;

class Helper
{
    /**
     * Make a string's first character lowercase.
     *
     * @param  string  $string
     * @return string
     */
    public static function lcfirst($string)
    {
        $str_p1 = mb_strtolower(mb_substr($string, 0, 1));
        $str_p2 = mb_substr($string, 1);

        return $str_p1.$str_p2;
    }

    /**
     * Make a string's first character uppercase.
     *
     * @param string $string
     * @return string
     */
    public static function ucfirst($string)
    {
        $str_p1 = mb_strtoupper(mb_substr($string, 0, 1));
        $str_p2 = mb_substr($string, 1);

        return $str_p1.$str_p2;
    }

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
     * @param string $string
     * @return string[]
     */
    public static function ucsplit($string)
    {
        return preg_split('/(?=\p{Lu})/u', $string, -1, PREG_SPLIT_NO_EMPTY);
    }
}
