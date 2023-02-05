<?php

namespace Realodix\ChangeCase\Support;

class Str
{
    /**
     * Make a string's first character lowercase.
     *
     * @param string $string
     * @return string
     */
    public static function lcfirst($string)
    {
        $str_p1 = \mb_strtolower(\mb_substr($string, 0, 1));
        $str_p2 = \mb_substr($string, 1);

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
        $str_p1 = \mb_strtoupper(\mb_substr($string, 0, 1));
        $str_p2 = \mb_substr($string, 1);

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
        $splitedString = \preg_split('/(?=\p{Lu})/u', $string, -1, PREG_SPLIT_NO_EMPTY);

        if ($splitedString === false) {
            return [''];
        }
        return $splitedString;
    }

    /**
     * Extracts a section of a string and returns it as a new string, without
     * modifying the original string. If $end is omitted, the function extracts
     * the remaining string. If $end is negative, it is computed from the end
     * of the string.
     *
     * @param int      $start Initial index from which to begin extraction.
     * @param int|null $end   Index at which to end extraction.
     * @return string
     */
    public static function str_slice(string $str, int $start, int $end = null)
    {
        $length = $end - $start;

        if ($end === null) {
            $length = \mb_strlen($str);
        } elseif ($end >= 0 && $end <= $start) {
            return '';
        }

        return \mb_substr($str, $start, $length);
    }
}
