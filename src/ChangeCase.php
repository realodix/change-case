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

    /**
     * The default options for the methods.
     *
     * ### Options
     * - delimiter: (string) This character separates each chunk of data within the text string.
     * - splitRx: (RegExp) Used to split into word segments.
     * - stripRx: (RegExp) Used to remove extraneous characters.
     * - separateNum: (bool) Used to separate numbers or not.
     * - apostrophe: (bool) Used to separate apostrophe or not.
     *
     * @param _Options $options
     * @return _Options
     */
    private static function defaultOptions(array $options): array
    {
        $resolver = new OptionsResolver;
        $resolver->setDefaults([
            'delimiter'   => ' ',
            // Support camel case ("camelCase" -> "camel Case" and "CAMELCase" -> "CAMEL Case")
            'splitRx'     => [
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
        $opts = $resolver->resolve($options);

        // Custom delimiters must pass through stripRx so that characters at
        // the edges are preserved
        if ($opts['delimiter'] !== ' ') {
            $escaped = preg_quote($opts['delimiter'], '/');
            $opts['stripRx'] = '/[^'.self::ALPHA_RX.self::NUM_RX.$escaped.']+/ui';
        }

        if ($opts['separateNum']) {
            $opts['splitRx'] = array_merge(
                $opts['splitRx'],
                [
                    '/(['.self::NUM_RX.'])(['.self::ALPHA_RX.'])/u',
                    '/(['.self::ALPHA_RX.'])(['.self::NUM_RX.'])/u',
                ],
            );
        }

        if ($opts['apostrophe']) {
            $opts['stripRx'] = '/[^'.self::ALPHA_RX.self::NUM_RX.'\']+/ui';
        }

        return $opts;
    }

    /**
     * Normalize a string into an array of words.
     *
     * @param _Options $options
     * @return string[]
     */
    private static function words(string $str, array $options = []): array
    {
        $opts = self::defaultOptions($options);

        // Support camelCase splitting (e.g., "camelCase" -> "camel Case")
        $str = preg_replace($opts['splitRx'], '$1 $2', $str);
        // Replace non-word characters/symbols with a space to avoid merging words
        $str = preg_replace($opts['stripRx'], ' ', $str);

        $str = trim($str);
        $str = preg_replace('/\s+/', ' ', $str);
        $words = explode(' ', $str);
        $words = array_values(array_filter($words, fn(string $w) => $w !== ''));

        return array_map('mb_strtolower', $words);
    }

    /**
     * Transform into a lower cased string with spaces between words,
     * and clean up the string from non-word characters.
     *
     * @param _Options $options
     */
    public static function no(string $str, array $options = []): string
    {
        $opts = self::defaultOptions($options);
        $delimiter = $opts['delimiter'];

        return implode($delimiter, self::words($str, $opts));
    }

    /**
     * Transform into a string with the separator denoted by the next word
     * capitalized.
     *
     * @param _Options $options
     */
    public static function camel(string $str, array $options = []): string
    {
        $words = self::words($str, $options);
        if (empty($words)) {
            return '';
        }

        // symfony/polyfill-php84
        return array_shift($words).implode('', array_map('mb_ucfirst', $words));
    }

    /**
     * Transform into upper case string with an underscore between words.
     */
    public static function constant(string $str): string
    {
        return mb_strtoupper(implode('_', self::words($str)));
    }

    /**
     * Transform into a lower case string with a period between words.
     *
     * @param _Options $options
     */
    public static function dot(string $str, array $options = []): string
    {
        $delimiter = $options['delimiter'] ?? '.';

        return implode($delimiter, self::words($str, $options));
    }

    /**
     * Transform into a dash separated string of capitalized words.
     *
     * @param _Options $options
     */
    public static function header(string $str, array $options = []): string
    {
        $delimiter = $options['delimiter'] ?? '-';
        $joined = implode($delimiter, self::words($str, $options));
        $escapedDelimiter = preg_quote($delimiter, '/');

        return preg_replace_callback(
            '/^.|'.$escapedDelimiter.'./u',
            fn(array $m) => mb_strtoupper($m[0]),
            $joined,
        );
    }

    /**
     * This method will convert strings delimited by casing, hyphens, or underscores
     * into a space delimited string with each word's first letter capitalized.
     */
    public static function headline(string $str): string
    {
        $parts = explode(' ', $str);
        $titleCase = fn($str) => mb_convert_case($str, MB_CASE_TITLE, 'UTF-8');

        $parts = count($parts) > 1
            ? array_map($titleCase, $parts)
            : array_map($titleCase, Str::ucsplit(implode('_', $parts)));

        $collapsed = str_replace(['-', '_', ' '], '_', implode('_', $parts));

        return implode(' ', array_filter(explode('_', $collapsed)));
    }

    /**
     * Transform into a lower cased string with dashes between words.
     *
     * @param _Options $options
     */
    public static function kebab(string $str, array $options = []): string
    {
        $delimiter = $options['delimiter'] ?? '-';

        return implode($delimiter, self::words($str, $options));
    }

    /**
     * Transform into a string of capitalized words without separators.
     *
     * @param _Options $options
     */
    public static function pascal(string $str, array $options = []): string
    {
        // symfony/polyfill-php84
        return implode('', array_map('mb_ucfirst', self::words($str, $options)));
    }

    /**
     * Transform into a lower case string with slashes between words.
     *
     * @param _Options $options
     */
    public static function path(string $str, array $options = []): string
    {
        $delimiter = $options['delimiter'] ?? '/';

        return implode($delimiter, self::words($str, $options));
    }

    /**
     * Transform into a lower case with spaces between words, then capitalize
     * the string.
     *
     * @param _Options $options
     */
    public static function sentence(string $str, array $options = []): string
    {
        $words = self::words($str, $options);
        if (empty($words)) {
            return '';
        }

        // symfony/polyfill-php84
        $words[0] = mb_ucfirst($words[0]);

        return implode(' ', $words);
    }

    /**
     * Transform into a lower case string with underscores between words.
     *
     * @param _Options $options
     */
    public static function snake(string $str, array $options = []): string
    {
        $delimiter = $options['delimiter'] ?? '_';
        $snakeOpt = array_merge([
            'stripRx' => '/(?!^_*)[^'.self::ALPHA_RX.self::NUM_RX.']+/ui',
        ], $options);

        return implode($delimiter, self::words($str, $snakeOpt));
    }

    /**
     * Transform a string by swapping every character from upper to lower case,
     * or lower to upper case.
     */
    public static function swap(string $str): string
    {
        return mb_strtolower($str) ^ mb_strtoupper($str) ^ $str;
    }

    /**
     * Convert the given string to title case.
     *
     * @deprecated Use `mb_convert_case($str, MB_CASE_TITLE)` instead.
     */
    public static function title(string $str): string
    {
        return mb_convert_case($str, MB_CASE_TITLE, 'UTF-8');
    }
}
