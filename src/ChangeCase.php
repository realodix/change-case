<?php

namespace Realodix\ChangeCase;

use Realodix\ChangeCase\Support\Str;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ChangeCase
{
    const ALPHA_RX = '\p{L}\p{M}';
    const NUM_RX = '\p{N}';
    const LO_CHAR_RX = '\p{Ll}\p{M}';
    const UP_CHAR_RX = '\p{Lu}\p{M}';

    /**
     * The default options for the methods. These are merged with the user supplied options.
     * The user supplied options take precedence.
     *
     * ### Options
     * - delimiter: (string) This character separates each chunk of data within the text string.
     * - splitRx: (RegExp) Used to split into word segments.
     * - stripRx: (RegExp) Used to remove extraneous characters.
     * - separateNum: (bool) Used to separate numbers or not.
     * - apostrophe: (bool) Used to separate apostrophe or not.
     */
    private static function defaultOptions(array $opt = []): array
    {
        $resolver = new OptionsResolver;
        $resolver->setDefaults([
            'delimiter'   => ' ',
            'splitRx'     => [
                // Support camel case ("camelCase" -> "camel Case" and "CAMELCase" -> "CAMEL Case")
                '/(['.self::LO_CHAR_RX.self::NUM_RX.'])(['.self::UP_CHAR_RX.'])/u',
                '/(['.self::UP_CHAR_RX.'])(['.self::UP_CHAR_RX.']['.self::LO_CHAR_RX.'])/u',
            ],
            // Remove all non-word characters
            'stripRx'     => '/[^'.self::ALPHA_RX.self::NUM_RX.']+/ui',
            'separateNum' => false,
            'apostrophe'  => false,
        ]);
        $resolver->setAllowedTypes('delimiter', 'string')
            ->setAllowedTypes('splitRx', ['string', 'string[]'])
            ->setAllowedTypes('stripRx', ['string', 'string[]'])
            ->setAllowedTypes('separateNum', 'bool')
            ->setAllowedTypes('apostrophe', 'bool');

        // Merge the user supplied options with the defaults.
        $options = $resolver->resolve($opt);

        if ($options['separateNum'] === true) {
            $options['splitRx'] = \array_merge(
                $options['splitRx'],
                [
                    '/(['.self::NUM_RX.'])(['.self::ALPHA_RX.'])/u',
                    '/(['.self::ALPHA_RX.'])(['.self::NUM_RX.'])/u',
                ],
            );
        }

        if ($options['apostrophe'] === true) {
            $options['stripRx'] = '/[^'.self::ALPHA_RX.self::NUM_RX.'\']+/ui';
        }

        return $options;
    }

    /**
     * Transform into a lower cased string with spaces between words, and clean
     * up the string from non-word characters.
     */
    public static function no(string $value, array $opt = []): string
    {
        $opt = self::defaultOptions($opt);

        // Replace all non-word characters with the delimiter (default or user supplied)
        // Like "foo-bar" -> "foo bar" or "foo_bar" -> "foo bar".
        $result = \preg_replace(
            $opt['stripRx'],
            $opt['delimiter'],
            \preg_replace($opt['splitRx'], '$1 $2', $value),
        );

        // Clean up excess delimiters
        $result = \trim(\preg_replace('/\s+/', ' ', $result));

        // Change the delimiter with the user's choice
        $result = \implode($opt['delimiter'], \explode(' ', $result));

        return \mb_strtolower($result);
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
            fn(array $matches) => \mb_strtoupper($matches[0]),
            self::no($str, \array_merge(['delimiter' => '-'], $opt)),
        );
    }

    /**
     * This method will convert strings delimited by casing, hyphens, or underscores
     * into a space delimited string with each word's first letter capitalized.
     */
    public static function headline(string $str): string
    {
        $parts = \explode(' ', $str);
        $titleCase = fn($str) => \mb_convert_case($str, MB_CASE_TITLE, 'UTF-8');

        $parts = \count($parts) > 1
            ? \array_map($titleCase, $parts)
            : \array_map($titleCase, Str::ucsplit(\implode('_', $parts)));

        $collapsed = \str_replace(['-', '_', ' '], '_', \implode('_', $parts));

        return \implode(' ', \array_filter(\explode('_', $collapsed)));
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
    public static function sentence(string $str, array $opt = []): string
    {
        return Str::ucfirst(self::no($str, $opt));
    }

    /**
     * Transform into a lower case string with underscores between words.
     */
    public static function snake(string $str, array $opt = []): string
    {
        $options = [
            'delimiter' => '_',
            'stripRx'   => '/(?!^_*)[^'.self::ALPHA_RX.self::NUM_RX.']+/ui',
        ];

        return self::no($str, \array_merge($options, $opt));
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
     *
     * @deprecated Use `mb_convert_case($str, MB_CASE_TITLE)` instead.
     */
    public static function title(string $str): string
    {
        return \mb_convert_case($str, MB_CASE_TITLE, 'UTF-8');
    }
}
