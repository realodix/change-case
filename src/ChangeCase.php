<?php

namespace Realodix\ChangeCase;

use Realodix\ChangeCase\Support\Str;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * @phpstan-type _Options array{
 *  delimiter?: string,
 *  splitRx?: array<string>,
 *  stripRx?: string|array<string>,
 *  separateNum?: bool,
 *  apostrophe?: bool
 * }
 */
class ChangeCase
{
    const ALPHA_RX = '\p{L}\p{M}';
    const NUM_RX = '\p{N}';
    const LO_CHAR_RX = '\p{Ll}\p{M}';
    const UP_CHAR_RX = '\p{Lu}\p{M}';

    private static ?OptionsResolver $resolver = null;

    private static function getResolver(): OptionsResolver
    {
        if (self::$resolver === null) {
            self::$resolver = new OptionsResolver();
            self::$resolver->setDefaults([
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
            self::$resolver->setAllowedTypes('delimiter', 'string')
                ->setAllowedTypes('splitRx', ['string', 'string[]'])
                ->setAllowedTypes('stripRx', ['string', 'string[]'])
                ->setAllowedTypes('separateNum', 'bool')
                ->setAllowedTypes('apostrophe', 'bool');
        }

        return self::$resolver;
    }

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
     *
     * @param _Options $options
     * @return array<string>
     */
    private static function defaultOptions(array $options = []): array
    {
        $opts = self::getResolver()->resolve($options);

        if ($opts['separateNum'] === true) {
            $opts['splitRx'] = \array_merge(
                $opts['splitRx'],
                [
                    '/(['.self::NUM_RX.'])(['.self::ALPHA_RX.'])/u',
                    '/(['.self::ALPHA_RX.'])(['.self::NUM_RX.'])/u',
                ],
            );
        }

        if ($opts['apostrophe'] === true) {
            $opts['stripRx'] = '/[^'.self::ALPHA_RX.self::NUM_RX.'\']+/ui';
        }

        return $opts;
    }

    /**
     * Transform into a lower cased string with spaces between words, and clean
     * up the string from non-word characters.
     *
     * @param _Options $options
     */
    public static function no(string $value, array $options = []): string
    {
        $opts = self::defaultOptions($options);

        // Replace all non-word characters with the delimiter (default or user supplied)
        // Like "foo-bar" -> "foo bar" or "foo_bar" -> "foo bar".
        $result = \preg_replace(
            $opts['stripRx'],
            $opts['delimiter'],
            \preg_replace($opts['splitRx'], '$1 $2', $value),
        );

        // Clean up excess delimiters
        $result = \trim(\preg_replace('/\s+/', ' ', $result));

        // Change the delimiter with the user's choice
        $result = \implode($opts['delimiter'], \explode(' ', $result));

        return \mb_strtolower($result);
    }

    /**
     * Transform into a string with the separator denoted by the next word
     * capitalized.
     *
     * @param _Options $options
     */
    public static function camel(string $str, array $options = []): string
    {
        // symfony/polyfill-php84
        return mb_lcfirst(self::pascal($str, $options));
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
     *
     * @param _Options $options
     */
    public static function dot(string $str, array $options = []): string
    {
        return self::no($str, \array_merge(['delimiter' => '.'], $options));
    }

    /**
     * Transform into a dash separated string of capitalized words.
     *
     * @param _Options $options
     */
    public static function header(string $str, array $options = []): string
    {
        return \preg_replace_callback(
            '/^.|-./u',
            fn(array $matches) => \mb_strtoupper($matches[0]),
            self::no($str, \array_merge(['delimiter' => '-'], $options)),
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
     *
     * @param _Options $options
     */
    public static function kebab(string $str, array $options = []): string
    {
        return self::no($str, \array_merge(['delimiter' => '-'], $options));
    }

    /**
     * Transform into a string of capitalized words without separators.
     *
     * @param _Options $options
     */
    public static function pascal(string $str, array $options = []): string
    {
        $value = self::headline(self::no($str, $options));

        return \str_ireplace(' ', '', $value);
    }

    /**
     * Transform into a lower case string with slashes between words.
     *
     * @param _Options $options
     */
    public static function path(string $str, array $options = []): string
    {
        return self::no($str, \array_merge(['delimiter' => '/'], $options));
    }

    /**
     * Transform into a lower case with spaces between words, then capitalize
     * the string.
     *
     * @param _Options $options
     */
    public static function sentence(string $str, array $options = []): string
    {
        // symfony/polyfill-php84
        return mb_ucfirst(self::no($str, $options));
    }

    /**
     * Transform into a lower case string with underscores between words.
     *
     * @param _Options $options
     */
    public static function snake(string $str, array $options = []): string
    {
        $opts = [
            'delimiter' => '_',
            'stripRx'   => '/(?!^_*)[^'.self::ALPHA_RX.self::NUM_RX.']+/ui',
        ];

        return self::no($str, \array_merge($opts, $options));
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
