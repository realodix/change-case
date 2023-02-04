<?php

namespace Realodix\ChangeCase;

class Helper
{
    /**
     * Make a string's first character lowercase.
     *
     * @param string $string
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

    /**
     * Returns the substring beginning at $start, and up to, but not including
     * the index specified by $end. If $end is omitted, the function extracts
     * the remaining string. If $end is negative, it is computed from the end
     * of the string.
     *
     * @param int      $start Initial index from which to begin extraction.
     * @param int|null $end   Index at which to end extraction.
     * @return string
     */
    public static function str_slice(string $str, int $start, int $end = null)
    {
        if ($end === null) {
            $length = (int) \mb_strlen($str);
        } elseif ($end >= 0 && $end <= $start) {
            return '';
        } elseif ($end < 0) {
            $length = (int) \mb_strlen($str) + $end - $start;
        } else {
            $length = $end - $start;
        }

        return \mb_substr($str, $start, $length);
    }
}
