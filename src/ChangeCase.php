<?php

namespace Realodix\ChangeCase;

use voku\helper\UTF8;

class ChangeCase
{
    /**
     * Transform into a lower cased string with spaces between words.
     *
     * ### Options
     * - delimiter          (String) Used between words
     * - splitRx        (Rx) Used to split into word segments
     * - splitNumberRx  (Rx) Used to split numbers
     * - stripRx        (Rx) Used to remove extraneous characters
     * - separateNumber     (Bool)   Used to separate numbers or not
     *
     * @param string $value
     * @param array  $opt
     *
     * @return string
     */
    public static function no(string $value, array $opt = []): string
    {
        // Support camel case ("camelCase" -> "camel Case" and "CAMELCase" -> "CAMEL Case")
        $splitRx = ['/([\p{Ll}|\p{M}\p{N}])([\p{Lu}|\p{M}])/u', '/([\p{Lu}|\p{M}])([\p{Lu}|\p{M}][\p{Ll}|\p{M}])/u'];
        // Regex to split numbers ("13test" -> "13 test")
        $splitNumberRx = array_merge($splitRx, ['/([\p{N}])([\p{L}|\p{M}])/u', '/([\p{L}|\p{M}])([\p{N}])/u']);
        // Remove all non-word characters
        $stripRx = '/[^\p{L}|\p{M}\p{N}]+/ui';

        $opt += [
            'delimiter'      => ' ',
            'splitRx'        => $splitRx,
            'splitNumberRx'  => $splitNumberRx,
            'stripRx'        => $stripRx,
            'separateNumber' => false,
        ];

        $splitRx = $opt['separateNumber'] ? $opt['splitNumberRx'] : $opt['splitRx'];

        $result = preg_replace(
            $opt['stripRx'],
            $opt['delimiter'],
            preg_replace($splitRx, '$1 $2', $value)
        );

        // Trim the delimiter from around the output string.
        $start = 0;
        $end = mb_strlen($result);
        while (UTF8::char_at($result, $start) === ' ') {
            $start++;
        }
        while (UTF8::char_at($result, $end - 1) === ' ') {
            $end--;
        }

        $slice = UTF8::str_slice($result, $start, $end);
        $split = explode(' ', $slice);
        $toLowerCase = array_map('mb_strtolower', $split);
        $join = implode($opt['delimiter'], $toLowerCase);

        return $join;
    }

    /**
     * Transform into a string with the separator denoted by the next word capitalized.
     *
     * @param string $string
     * @param array  $opt
     *
     * @return string
     */
    public static function camel(string $string, array $opt = []): string
    {
        return UTF8::lcfirst(self::pascal($string, $opt));
    }

    /**
     * Transform into a space separated string with each word capitalized.
     *
     * @param string $string
     *
     * @return string
     */
    public static function capital(string $string): string
    {
        return preg_replace_callback(
            '/^.| ./u',
            function (array $matches) {
                return mb_strtoupper($matches[0]);
            },
            self::no($string)
        );
    }

    /**
     * Transform into upper case string with an underscore between words.
     *
     * @param string $string
     *
     * @return string
     */
    public static function constant(string $string): string
    {
        return mb_strtoupper(self::snake($string));
    }

    /**
     * Transform into a lower case string with a period between words.
     *
     * @param string $string
     * @param array  $opt
     *
     * @return string
     */
    public static function dot(string $string, array $opt = []): string
    {
        return self::no($string, $opt += ['delimiter' => '.']);
    }

    /**
     * Transform into a dash separated string of capitalized words.
     *
     * @param string $string
     * @param array  $opt
     *
     * @return string
     */
    public static function header(string $string, array $opt = []): string
    {
        return preg_replace_callback(
            '/^.|-./u',
            function (array $matches) {
                return mb_strtoupper($matches[0]);
            },
            self::no($string, $opt += ['delimiter' => '-'])
        );
    }

    /**
     * Transform into a string of capitalized words without separators.
     *
     * @param string $string
     * @param array  $opt
     *
     * @return string
     */
    public static function pascal(string $string, array $opt = []): string
    {
        $value = UTF8::ucwords(self::no($string, $opt));

        return UTF8::str_ireplace(' ', '', $value);
    }

    /**
     * Transform into a lower case string with slashes between words.
     *
     * @param string $string
     * @param array  $opt
     *
     * @return string
     */
    public static function path(string $string, array $opt = []): string
    {
        return self::no($string, $opt += ['delimiter' => '/']);
    }

    /**
     * Transform into a lower case with spaces between words, then capitalize the string.
     *
     * @param string $string
     *
     * @return string
     */
    public static function sentence(string $string): string
    {
        return UTF8::ucfirst(self::no($string));
    }

    /**
     * Transform into a lower case string with underscores between words.
     *
     * @param string $string
     * @param array  $opt
     *
     * @return string
     */
    public static function snake(string $string, array $opt = []): string
    {
        $stripRx = '/(?!^_*)[^\p{L}|\p{M}\p{N}]+/ui';

        return self::no(
            $string,
            $opt += ['delimiter' => '_', 'stripRx' => $stripRx]
        );
    }

    /**
     * Transform into a lower cased string with dashes between words.
     *
     * @param string $string
     * @param array  $opt
     *
     * @return string
     */
    public static function spinal(string $string, array $opt = []): string
    {
        return self::no($string, $opt += ['delimiter' => '-']);
    }

    /**
     * Transform a string by swapping every character from upper to lower case, or lower
     * to upper case.
     *
     * @param string $string
     *
     * @return string
     */
    public static function swap(string $string): string
    {
        return mb_strtolower($string) ^ mb_strtoupper($string) ^ $string;
    }

    /**
     * Transform a string into title case following English rules.
     *
     * @param string $string
     * @param array  $ignore An array of words not to capitalize.
     *
     * @return string
     */
    public static function title(string $string, array $ignore = []): string
    {
        $smallWords = ['nor', 'over', 'upon'];

        return UTF8::str_titleize_for_humans($string, array_merge($smallWords, $ignore));
    }
}
