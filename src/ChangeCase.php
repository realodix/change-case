<?php

namespace Realodix\ChangeCase;

use Realodix\ChangeCase\Support\Str;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ChangeCase
{
    const ALPHA_RX = '\p{L}|\p{M}';
    const NUM_RX = '\p{N}';
    const LO_CHAR_RX = '\p{Ll}|\p{M}';
    const UP_CHAR_RX = '\p{Lu}|\p{M}';

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
                ['/(['.self::NUM_RX.'])(['.self::ALPHA_RX.'])/u', '/(['.self::ALPHA_RX.'])(['.self::NUM_RX.'])/u']
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
            \preg_replace($opt['splitRx'], '$1 $2', $value)
        );

        // Trim the delimiter from around the output string. This is done to ensure that
        // the output is not " foo bar ". This is also done to ensure that the output is
        // not "foo bar " (note the extra space at the end).
        $start = 0;
        $end = \mb_strlen($result);
        while (\mb_substr($result, $start, 1) === ' ') {
            $start++;
        }
        while (\mb_substr($result, $end - 1, 1) === ' ') {
            $end--;
        }
        $result = \mb_substr($result, $start, $end - $start);

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
     * Transform into a space separated string with each word capitalized.
     *
     * @deprecated Will be removed in v4.0.0
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
        return self::no($str, $opt += ['delimiter' => '.']);
    }

    /**
     * Transform into a dash separated string of capitalized words.
     */
    public static function header(string $str, array $opt = []): string
    {
        return \preg_replace_callback(
            '/^.|-./u',
            fn (array $matches) => \mb_strtoupper($matches[0]),
            self::no($str, $opt += ['delimiter' => '-'])
        );
    }

    /**
     * Convert the given string to title case for each word.
     */
    public static function headline(string $str): string
    {
        $parts = \explode(' ', $str);

        $parts = \count($parts) > 1
            ? \array_map([static::class, 'title'], $parts)
            : \array_map([static::class, 'title'], Str::ucsplit(\implode('_', $parts)));

        $collapsed = \str_replace(['-', '_', ' '], '_', \implode('_', $parts));

        return \implode(' ', \array_filter(\explode('_', $collapsed)));
    }

    /**
     * Transform into a lower cased string with dashes between words.
     */
    public static function kebab(string $str, array $opt = []): string
    {
        return self::no($str, $opt += ['delimiter' => '-']);
    }

    /**
     * @deprecated Use kebab() instead
     */
    public static function spinal(string $str, array $opt = []): string
    {
        return self::kebab($str, $opt);
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
        return self::no($str, $opt += ['delimiter' => '/']);
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
        return self::no($str, $opt += [
            'delimiter' => '_',
            'stripRx'   => '/(?!^_*)[^'.self::ALPHA_RX.self::NUM_RX.']+/ui',
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
