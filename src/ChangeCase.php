<?php

namespace Realodix\ChangeCase;

use Realodix\ChangeCase\Support\Str;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ChangeCase
{
    const ALPHA_RX = '\p{L}|\p{M}';

    const NUM_RX = '\p{N}';

    /**
     * The default options for the methods.
     *
     * ### Options
     * - delimiter: (string) This character separates each chunk of data within the text string.
     * - splitRx: (RegExp) Used to split into word segments.
     * - stripRx: (RegExp) Used to remove extraneous characters.
     * - separateNum: (bool) Used to separate numbers or not.
     *
     * @return array
     */
    private static function options(array $opt = [])
    {
        $loCharRx = '\p{Ll}|\p{M}';
        $upCharRx = '\p{Lu}|\p{M}';

        // Support camel case ("camelCase" -> "camel Case" and "CAMELCase" -> "CAMEL Case")
        $splitRx = [
            '/(['.$loCharRx.self::NUM_RX.'])(['.$upCharRx.'])/u',
            '/(['.$upCharRx.'])(['.$upCharRx.']['.$loCharRx.'])/u',
        ];

        // Remove all non-word characters
        $stripRx = '/[^'.self::ALPHA_RX.self::NUM_RX.']+/ui';

        $resolver = new OptionsResolver;
        $resolver->setDefaults([
            'delimiter'   => ' ',
            'splitRx'     => $splitRx,
            'stripRx'     => $stripRx,
            'separateNum' => false,
            'apostrophe'  => false,
        ]);
        $resolver->setAllowedTypes('delimiter', 'string')
            ->setAllowedTypes('splitRx', ['string', 'string[]'])
            ->setAllowedTypes('stripRx', ['string', 'string[]'])
            ->setAllowedTypes('separateNum', 'bool');

        return $resolver->resolve($opt);
    }

    /**
     * Transform into a lower cased string with spaces between words.
     */
    public static function no(string $value, array $opt = []): string
    {
        $opt = self::options($opt);

        // Regex to split numbers ("13test" -> "13 test")
        $splitNumRx = \array_merge(
            (array) $opt['splitRx'],
            ['/(['.self::NUM_RX.'])(['.self::ALPHA_RX.'])/u', '/(['.self::ALPHA_RX.'])(['.self::NUM_RX.'])/u']
        );
        $splitRx = $opt['separateNum'] ? $splitNumRx : $opt['splitRx'];
        // Allow apostrophes to be included in words
        $stripRx = $opt['apostrophe'] ? '/[^'.self::ALPHA_RX.self::NUM_RX.'\']+/ui' : $opt['stripRx'];

        $result = \preg_replace(
            $stripRx,
            $opt['delimiter'],
            \preg_replace($splitRx, '$1 $2', $value)
        );

        // Trim the delimiter from around the output string.
        $start = 0;
        $end = \mb_strlen($result);
        while (\mb_substr($result, $start, 1) === ' ') {
            $start++;
        }
        while (\mb_substr($result, $end - 1, 1) === ' ') {
            $end--;
        }

        return collect(\explode(' ', Str::str_slice($result, $start, $end)))
            ->map(fn ($item) => \mb_strtolower($item)) // Convert to lower case.
            ->implode($opt['delimiter']);
    }

    /**
     * Transform into a string with the separator denoted by the next word
     * capitalized.
     */
    public static function camel(string $str, array $opt = []): string
    {
        return Str::lcfirst(self::pascal($str, $opt));
    }

    /**
     * Transform into a space separated string with each word capitalized.
     */
    public static function capital(string $str): string
    {
        $strToUpper = fn (array $matches) => \mb_strtoupper($matches[0]);

        return \preg_replace_callback('/^.| ./u', $strToUpper, self::no($str));
    }

    /**
     * Transform into upper case string with an underscore between words.
     */
    public static function constant(string $str): string
    {
        return \mb_strtoupper(self::snake($str));
    }

    /**
     * Transform into a lower case string with a period between words.
     */
    public static function dot(string $str, array $opt = []): string
    {
        return self::no($str, \array_merge(['delimiter' => '.'], $opt));
    }

    /**
     * Transform into a dash separated string of capitalized words.
     */
    public static function header(string $str, array $opt = []): string
    {
        return \preg_replace_callback(
            '/^.|-./u',
            fn (array $matches) => \mb_strtoupper($matches[0]),
            self::no($str, \array_merge(['delimiter' => '-'], $opt))
        );
    }

    /**
     * Transform into a space separated string of capitalized words.
     */
    public static function headline(string $str): string
    {
        $parts = \explode(' ', $str);

        $parts = \count($parts) > 1
            ? \array_map([static::class, 'title'], $parts)
            : \array_map([static::class, 'title'], Str::ucsplit(\implode('_', $parts)));

        $collapsed = \str_replace(['-', '_', ' '], '_', \implode('_', $parts));

        return collect(\explode('_', $collapsed))->filter()->implode(' ');
    }

    /**
     * Transform into a lower cased string with dashes between words.
     */
    public static function kebab(string $str, array $opt = []): string
    {
        return self::no($str, \array_merge(['delimiter' => '-'], $opt));
    }

    /**
     * Transform into a string of capitalized words without separators.
     */
    public static function pascal(string $str, array $opt = []): string
    {
        $value = self::headline(self::no($str, $opt));

        return \str_ireplace(' ', '', $value);
    }

    /**
     * Transform into a lower case string with slashes between words.
     */
    public static function path(string $str, array $opt = []): string
    {
        return self::no($str, \array_merge(['delimiter' => '/'], $opt));
    }

    /**
     * Transform into a lower case with spaces between words, then capitalize
     * the string.
     */
    public static function sentence(string $str): string
    {
        return Str::ucfirst(self::no($str));
    }

    /**
     * Transform into a lower case string with underscores between words.
     */
    public static function snake(string $str, array $opt = []): string
    {
        $alphaNumRx = '\p{L}|\p{M}\p{N}';

        return self::no($str, $opt += [
            'delimiter' => '_',
            'stripRx'   => '/(?!^_*)[^'.$alphaNumRx.']+/ui',
        ]);
    }

    /**
     * Transform a string by swapping every character from upper to lower case,
     * or lower to upper case.
     */
    public static function swap(string $str): string
    {
        return \mb_strtolower($str) ^ \mb_strtoupper($str) ^ $str;
    }

    /**
     * Convert the given string to title case.
     */
    public static function title(string $str): string
    {
        return \mb_convert_case($str, MB_CASE_TITLE, 'UTF-8');
    }
}
