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
     * - splitRegexp        (RegExp) Used to split into word segments
     * - splitNumberRegexp  (RegExp) Used to split numbers
     * - stripRegexp        (RegExp) Used to remove extraneous characters
     * - separateNumber     (Bool)   Used to separate numbers or not
     */
    public static function no(string $value, array $opt = []): string
    {
        $alphaRx = '\p{L}|\p{M}';
        $loCharRx = '\p{Ll}|\p{M}';
        $upCharRx = '\p{Lu}|\p{M}';
        $numRx = '\p{N}';

        // Support camel case ("camelCase" -> "camel Case" and "CAMELCase" -> "CAMEL Case")
        $splitRx = [
            '/(['.$loCharRx.$numRx.'])(['.$upCharRx.'])/u',
            '/(['.$upCharRx.'])(['.$upCharRx.']['.$loCharRx.'])/u',
        ];

        // Regex to split numbers ("13test" -> "13 test")
        $splitNumRx = array_merge(
            $splitRx,
            ['/(['.$numRx.'])(['.$alphaRx.'])/u', '/(['.$alphaRx.'])(['.$numRx.'])/u']
        );

        // Remove all non-word characters
        $stripRx = '/[^'.$alphaRx.$numRx.']+/ui';

        $opt += [
            'delimiter'   => ' ',
            'splitRx'     => $splitRx,
            'splitNumRx'  => $splitNumRx,
            'stripRx'     => $stripRx,
            'separateNum' => false,
        ];

        $splitRx = $opt['separateNum'] ? $opt['splitNumRx'] : $opt['splitRx'];

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

        return implode($opt['delimiter'], $toLowerCase);
    }

    /**
     * Transform into a string with the separator denoted by the next word capitalized.
     */
    public static function camel(string $str, array $opt = []): string
    {
        return UTF8::lcfirst(self::pascal($str, $opt));
    }

    /**
     * Transform into a space separated string with each word capitalized.
     */
    public static function capital(string $str): string
    {
        $strToUpper = fn (array $matches) => mb_strtoupper($matches[0]);

        return preg_replace_callback('/^.| ./u', $strToUpper, self::no($str));
    }

    /**
     * Transform into upper case string with an underscore between words.
     */
    public static function constant(string $str): string
    {
        return mb_strtoupper(self::snake($str));
    }

    /**
     * Transform into a lower case string with a period between words.
     */
    public static function dot(string $str, array $opt = []): string
    {
        return self::no($str, $opt += ['delimiter' => '.']);
    }

    /**
     * Transform into a dash separated string of capitalized words.
     */
    public static function header(string $str, array $opt = []): string
    {
        return preg_replace_callback(
            '/^.|-./u',
            fn (array $matches) => mb_strtoupper($matches[0]),
            self::no($str, $opt += ['delimiter' => '-'])
        );
    }

    /**
     * Transform into a string of capitalized words without separators.
     */
    public static function pascal(string $str, array $opt = []): string
    {
        $value = UTF8::ucwords(self::no($str, $opt));

        return str_ireplace(' ', '', $value);
    }

    /**
     * Transform into a lower case string with slashes between words.
     */
    public static function path(string $str, array $opt = []): string
    {
        return self::no($str, $opt += ['delimiter' => '/']);
    }

    /**
     * Transform into a lower case with spaces between words, then capitalize the string.
     */
    public static function sentence(string $str): string
    {
        return UTF8::ucfirst(self::no($str));
    }

    /**
     * Transform into a lower case string with underscores between words.
     */
    public static function snake(string $str, array $opt = []): string
    {
        $alphaNumRx = '\p{L}|\p{M}\p{N}';

        $stripRx = '/(?!^_*)[^'.$alphaNumRx.']+/ui';

        return self::no(
            $str,
            $opt += ['delimiter' => '_', 'stripRx' => $stripRx]
        );
    }

    /**
     * Transform into a lower cased string with dashes between words.
     */
    public static function spinal(string $str, array $opt = []): string
    {
        return self::no($str, $opt += ['delimiter' => '-']);
    }

    /**
     * Transform a string by swapping every character from upper to lower case, or lower
     * to upper case.
     */
    public static function swap(string $str): string
    {
        return mb_strtolower($str) ^ mb_strtoupper($str) ^ $str;
    }

    /**
     * Transform a string into title case following English rules.
     */
    public static function title(string $str, array $ignore = []): string
    {
        return UTF8::str_titleize_for_humans($str, $ignore);
    }
}
