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
     *
     * @param string $value
     * @param array  $opt
     *
     * @return string
     */
    public static function no(string $value, array $opt = []): string
    {
        // Support camel case ("camelCase" -> "camel Case" and "CAMELCase" -> "CAMEL Case")
        $splitRegexp = ['/([\p{Ll}|\p{M}\p{N}])([\p{Lu}|\p{M}])/u', '/([\p{Lu}|\p{M}])([\p{Lu}|\p{M}][\p{Ll}|\p{M}])/u'];
        // Regex to split numbers ("13test" -> "13 test")
        $splitNumberRegexp = array_merge($splitRegexp, ['/([\p{N}])([\p{L}|\p{M}])/u', '/([\p{L}|\p{M}])([\p{N}])/u']);
        // Remove all non-word characters
        $stripRegexp = '/[^\p{L}|\p{M}\p{N}]+/ui';

        $opt += [
            'delimiter'         => ' ',
            'splitRegexp'       => $splitRegexp,
            'splitNumberRegexp' => $splitNumberRegexp,
            'stripRegexp'       => $stripRegexp,
            'separateNumber'    => false,
        ];

        $splitRegexp = $opt['separateNumber'] ? $opt['splitNumberRegexp'] : $opt['splitRegexp'];

        $result = preg_replace(
            $opt['stripRegexp'],
            $opt['delimiter'],
            preg_replace($splitRegexp, '$1 $2', $value)
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
        $stripRegexp = '/(?!^_*)[^\p{L}|\p{M}\p{N}]+/ui';

        return self::no(
            $string,
            $opt += ['delimiter' => '_', 'stripRegexp' => $stripRegexp]
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

    /**
     * Returns a trimmed string in proper title case.
     *
     * Also accepts an array, $ignore, allowing you to list words not to be
     * capitalized.
     *
     * Adapted from John Gruber's script.
     *
     * @see https://gist.github.com/gruber/9f9e8650d68b13ce4d78
     *
     * @param string $str
     * @param array  $ignore   <p>An array of words not to capitalize.</p>
     * @param string $encoding [optional] <p>Set the charset for e.g. "mb_" function</p>
     *
     * @psalm-pure
     *
     * @return string
     *                <p>The titleized string.</p>
     */
    public static function str_titleize_for_humans(
        string $str,
        array $ignore = [],
        string $encoding = 'UTF-8'
    ): string {
        if ($str === '') {
            return '';
        }

        $small_words = [
            '(?<!q&)a',
            'an',
            'and',
            'as',
            'at(?!&t)',
            'but',
            'by',
            'en',
            'for',
            'if',
            'in',
            'of',
            'on',
            'or',
            'the',
            'to',
            'v[.]?',
            'via',
            'vs[.]?',
        ];

        if ($ignore !== []) {
            $small_words = \array_merge($small_words, $ignore);
        }

        $small_words_rx = \implode('|', $small_words);
        $apostrophe_rx = '(?x: [\'’] [[:lower:]]* )?';

        $str = \trim($str);

        if (!self::has_lowercase($str)) {
            $str = self::strtolower($str, $encoding);
        }

        // the main substitutions
        /** @noinspection RegExpDuplicateAlternationBranch - false-positive - https://youtrack.jetbrains.com/issue/WI-51002 */
        $str = (string) \preg_replace_callback(
            '~\\b (_*) (?:                                                           # 1. Leading underscore and
                        ( (?<=[ ][/\\\\]) [[:alpha:]]+ [-_[:alpha:]/\\\\]+ |                # 2. file path or
                          [-_[:alpha:]]+ [@.:] [-_[:alpha:]@.:/]+ ' . $apostrophe_rx . ' )  #    URL, domain, or email
                        |
                        ( (?i: ' . $small_words_rx . ' ) ' . $apostrophe_rx . ' )           # 3. or small word (case-insensitive)
                        |
                        ( [[:alpha:]] [[:lower:]\'’()\[\]{}]* ' . $apostrophe_rx . ' )     # 4. or word w/o internal caps
                        |
                        ( [[:alpha:]] [[:alpha:]\'’()\[\]{}]* ' . $apostrophe_rx . ' )     # 5. or some other word
                      ) (_*) \\b                                                          # 6. With trailing underscore
                    ~ux',
            /**
             * @param string[] $matches
             *
             * @psalm-pure
             *
             * @return string
             */
            static function (array $matches) use ($encoding): string {
                // preserve leading underscore
                $str = $matches[1];
                if ($matches[2]) {
                    // preserve URLs, domains, emails and file paths
                    $str .= $matches[2];
                } elseif ($matches[3]) {
                    // lower-case small words
                    $str .= self::strtolower($matches[3], $encoding);
                } elseif ($matches[4]) {
                    // capitalize word w/o internal caps
                    $str .= static::ucfirst($matches[4], $encoding);
                } else {
                    // preserve other kinds of word (iPhone)
                    $str .= $matches[5];
                }
                // preserve trailing underscore
                $str .= $matches[6];

                return $str;
            },
            $str
        );

        // Exceptions for small words: capitalize at start of title...
        $str = (string) \preg_replace_callback(
            '~(  \\A [[:punct:]]*            # start of title...
                      |  [:.;?!][ ]+                # or of subsentence...
                      |  [ ][\'"“‘(\[][ ]* )        # or of inserted subphrase...
                      ( ' . $small_words_rx . ' ) \\b # ...followed by small word
                     ~uxi',
            /**
             * @param string[] $matches
             *
             * @psalm-pure
             *
             * @return string
             */
            static function (array $matches) use ($encoding): string {
                return $matches[1] . static::ucfirst($matches[2], $encoding);
            },
            $str
        );

        // ...and end of title
        $str = (string) \preg_replace_callback(
            '~\\b ( ' . $small_words_rx . ' ) # small word...
                      (?= [[:punct:]]* \Z          # ...at the end of the title...
                      |   [\'"’”)\]] [ ] )         # ...or of an inserted subphrase?
                     ~uxi',
            /**
             * @param string[] $matches
             *
             * @psalm-pure
             *
             * @return string
             */
            static function (array $matches) use ($encoding): string {
                return static::ucfirst($matches[1], $encoding);
            },
            $str
        );

        // Exceptions for small words in hyphenated compound words.
        // e.g. "in-flight" -> In-Flight
        $str = (string) \preg_replace_callback(
            '~\\b
                        (?<! -)                   # Negative lookbehind for a hyphen; we do not want to match man-in-the-middle but do want (in-flight)
                        ( ' . $small_words_rx . ' )
                        (?= -[[:alpha:]]+)        # lookahead for "-someword"
                       ~uxi',
            /**
             * @param string[] $matches
             *
             * @psalm-pure
             *
             * @return string
             */
            static function (array $matches) use ($encoding): string {
                return static::ucfirst($matches[1], $encoding);
            },
            $str
        );

        // e.g. "Stand-in" -> "Stand-In" (Stand is already capped at this point)
        $str = (string) \preg_replace_callback(
            '~\\b
                      (?<!…)                    # Negative lookbehind for a hyphen; we do not want to match man-in-the-middle but do want (stand-in)
                      ( [[:alpha:]]+- )         # $1 = first word and hyphen, should already be properly capped
                      ( ' . $small_words_rx . ' ) # ...followed by small word
                      (?!	- )                 # Negative lookahead for another -
                     ~uxi',
            /**
             * @param string[] $matches
             *
             * @psalm-pure
             *
             * @return string
             */
            static function (array $matches) use ($encoding): string {
                return $matches[1] . static::ucfirst($matches[2], $encoding);
            },
            $str
        );

        return $str;
    }
}
