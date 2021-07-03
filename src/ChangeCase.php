<?php

namespace Realodix\ChangeCase;

use Realodix\Utils\Str;

class ChangeCase
{
    const ALPHA_RX = '\p{L}|\p{M}';

    const LO_CHAR_RX = '\p{Ll}|\p{M}';

    const UP_CHAR_RX = '\p{Lu}|\p{M}';

    /**
     * Transform into a lower cased string with spaces between words.
     *
     * ### Options
     * - delimiter    (String) Used between words
     * - splitRx      (Rx) Used to split into word segments
     * - splitNumRx   (Rx) Used to split numbers
     * - stripRx      (Rx) Used to remove extraneous characters
     * - separateNum  (Bool)   Used to separate numbers or not
     *
     * @param string $value
     * @param array  $opt
     *
     * @return string
     */
    public static function no(string $value, array $opt = []): string
    {
        // Support camel case ("camelCase" -> "camel Case" and "CAMELCase" -> "CAMEL Case")
        $splitRx = [
            '/(['.self::LO_CHAR_RX.'\p{N}])(['.self::UP_CHAR_RX.'])/u',
            '/(['.self::UP_CHAR_RX.'])(['.self::UP_CHAR_RX.']['.self::LO_CHAR_RX.'])/u'
        ];

        // Regex to split numbers ("13test" -> "13 test")
        $splitNumRx = array_merge(
            $splitRx,
            ['/([\p{N}])(['.self::ALPHA_RX.'])/u', '/(['.self::ALPHA_RX.'])([\p{N}])/u']
        );

        // Remove all non-word characters
        $stripRx = '/[^'.self::ALPHA_RX.'\p{N}]+/ui';

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
        while (Str::charAt($result, $start) === ' ') {
            $start++;
        }
        while (Str::charAt($result, $end - 1) === ' ') {
            $end--;
        }

        $slice = Str::slice($result, $start, $end);
        $split = explode(' ', $slice);
        $toLowerCase = array_map('mb_strtolower', $split);
        $join = implode($opt['delimiter'], $toLowerCase);

        return $join;
    }

    /**
     * Transform into a string with the separator denoted by the next word capitalized.
     *
     * @param string $str
     * @param array  $opt
     *
     * @return string
     */
    public static function camel(string $str, array $opt = []): string
    {
        return Str::lcfirst(self::pascal($str, $opt));
    }

    /**
     * Transform into a space separated string with each word capitalized.
     *
     * @param string $str
     *
     * @return string
     */
    public static function capital(string $str): string
    {
        return preg_replace_callback(
            '/^.| ./u',
            function (array $matches) {
                return mb_strtoupper($matches[0]);
            },
            self::no($str)
        );
    }

    /**
     * Transform into upper case string with an underscore between words.
     *
     * @param string $str
     *
     * @return string
     */
    public static function constant(string $str): string
    {
        return mb_strtoupper(self::snake($str));
    }

    /**
     * Transform into a lower case string with a period between words.
     *
     * @param string $str
     * @param array  $opt
     *
     * @return string
     */
    public static function dot(string $str, array $opt = []): string
    {
        return self::no($str, $opt += ['delimiter' => '.']);
    }

    /**
     * Transform into a dash separated string of capitalized words.
     *
     * @param string $str
     * @param array  $opt
     *
     * @return string
     */
    public static function header(string $str, array $opt = []): string
    {
        return preg_replace_callback(
            '/^.|-./u',
            function (array $matches) {
                return mb_strtoupper($matches[0]);
            },
            self::no($str, $opt += ['delimiter' => '-'])
        );
    }

    /**
     * Transform into a string of capitalized words without separators.
     *
     * @param string $str
     * @param array  $opt
     *
     * @return string
     */
    public static function pascal(string $str, array $opt = []): string
    {
        $value = Str::ucwords(self::no($str, $opt));

        return str_ireplace(' ', '', $value);
    }

    /**
     * Transform into a lower case string with slashes between words.
     *
     * @param string $str
     * @param array  $opt
     *
     * @return string
     */
    public static function path(string $str, array $opt = []): string
    {
        return self::no($str, $opt += ['delimiter' => '/']);
    }

    /**
     * Transform into a lower case with spaces between words, then capitalize the string.
     *
     * @param string $str
     *
     * @return string
     */
    public static function sentence(string $str): string
    {
        return Str::ucfirst(self::no($str));
    }

    /**
     * Transform into a lower case string with underscores between words.
     *
     * @param string $str
     * @param array  $opt
     *
     * @return string
     */
    public static function snake(string $str, array $opt = []): string
    {
        $stripRx = '/(?!^_*)[^'.self::ALPHA_RX.'\p{N}]+/ui';

        return self::no(
            $str,
            $opt += ['delimiter' => '_', 'stripRx' => $stripRx]
        );
    }

    /**
     * Transform into a lower cased string with dashes between words.
     *
     * @param string $str
     * @param array  $opt
     *
     * @return string
     */
    public static function spinal(string $str, array $opt = []): string
    {
        return self::no($str, $opt += ['delimiter' => '-']);
    }

    /**
     * Transform a string by swapping every character from upper to lower case, or lower
     * to upper case.
     *
     * @param string $str
     *
     * @return string
     */
    public static function swap(string $str): string
    {
        return mb_strtolower($str) ^ mb_strtoupper($str) ^ $str;
    }

    /**
     * Transform a string into title case following English rules.
     *
     * @param string $str
     * @param array  $ignore An array of words not to capitalize.
     *
     * @return string
     */
    public static function title(string $str, array $ignore = []): string
    {
        $smallWords = [
            '(?<!q&)a', 'an', 'and', 'as', 'at(?!&t)', 'but', 'by', 'en', 'for', 'if', 'in',
            'of', 'on', 'or', 'the', 'to', 'v[.]?', 'via', 'vs[.]?',
            'nor', 'over', 'upon',
        ];

        $alphaRx = '[:alpha:]';
        $lowerRx = '[:lower:]';

        if ($ignore !== []) {
            $smallWords = array_merge($smallWords, $ignore);
        }

        $smallWordsRx = implode('|', $smallWords);
        $apostropheRx = '(?x: [\'’] ['.$lowerRx.']* )?';

        $str = trim($str);

        if (! Str::hasLowercase($str)) {
            $str = strtolower($str);
        }

        // the main substitutions
        $str = preg_replace_callback(
            '~\\b (_*)
            (?:                                                                      # 1. Leading underscore and
                ( (?<=[ ][/\\\\]) ['.$alphaRx.']+ [-_'.$alphaRx.'/\\\\]+ |           # 2. file path or
                  [-_'.$alphaRx.']+ [@.:] [-_'.$alphaRx.'@.:/]+ '.$apostropheRx.' )  #    URL, domain, or email
                | ((?i: '.$smallWordsRx.') '.$apostropheRx.')                        # 3. or small word (case-insensitive)
                | (['.$alphaRx.'] ['.$lowerRx.'\'’()\[\]{}]* '.$apostropheRx.')      # 4. or word w/o internal caps
                | (['.$alphaRx.'] ['.$alphaRx.'\'’()\[\]{}]* '.$apostropheRx.')      # 5. or some other word
            )
            (_*) \\b                                                                 # 6. With trailing underscore
            ~ux',

            /**
             * @param string[] $matches
             *
             * @return string
             */
            static function (array $matches): string {
                // preserve leading underscore
                $str = $matches[1];
                if ($matches[2]) {
                    // preserve URLs, domains, emails and file paths
                    $str .= $matches[2];
                } elseif ($matches[3]) {
                    // lower-case small words
                    $str .= strtolower($matches[3]);
                } elseif ($matches[4]) {
                    // capitalize word w/o internal caps
                    $str .= Str::ucfirst($matches[4]);
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
        $str = preg_replace_callback(
            '~( \\A
                [[:punct:]]*         # start of title...
                | [:.;?!][ ]+        # or of subsentence...
                | [ ][\'"“‘(\[][ ]*  # or of inserted subphrase...
              )
              ('.$smallWordsRx.')    # ...followed by small word
            \\b
            ~uxi',

            /**
             * @param string[] $matches
             *
             * @return string
             */
            static function (array $matches): string {
                return $matches[1].Str::ucfirst($matches[2]);
            },
            $str
        );

        // ...and end of title
        $str = preg_replace_callback(
            '~\\b ('.$smallWordsRx.')   # small word...
                  (?= [[:punct:]]* \Z   # ...at the end of the title...
                  |   [\'"’”)\]] [ ] )  # ...or of an inserted subphrase?
            ~uxi',

            /**
             * @param string[] $matches
             *
             * @return string
             */
            static function (array $matches): string {
                return Str::ucfirst($matches[1]);
            },
            $str
        );

        // Exceptions for small words in hyphenated compound words.
        // e.g. "in-flight" -> In-Flight
        $str = preg_replace_callback(
            '~\\b
                (?<! -)                # Negative lookbehind for a hyphen; we do not want to match
                                       # man-in-the-middle but do want (in-flight)
                ('.$smallWordsRx.')
                (?= -['.$alphaRx.']+)  # lookahead for "-someword"
            ~uxi',

            /**
             * @param string[] $matches
             *
             * @return string
             */
            static function (array $matches): string {
                return Str::ucfirst($matches[1]);
            },
            $str
        );

        // e.g. "Stand-in" -> "Stand-In" (Stand is already capped at this point)
        $str = preg_replace_callback(
            '~\\b
                (?<!…)               # Negative lookbehind for a hyphen; we do not want to match
                                     # man-in-the-middle but do want (stand-in)
                (['.$alphaRx.']+-)   # $1 = first word and hyphen, should already be properly capped
                ('.$smallWordsRx.')  # ...followed by small word
                (?!	- )              # Negative lookahead for another -
            ~uxi',

            /**
             * @param string[] $matches
             *
             * @return string
             */
            static function (array $matches): string {
                return $matches[1].Str::ucfirst($matches[2]);
            },
            $str
        );

        return $str;
    }
}
