<?php

namespace Realodix\ChangeCase;

use voku\helper\UTF8;

class ChangeCase
{
    /**
     * Transform into a lower cased string with spaces between words.
     *
     * @param mixed $value
     * @param array $options
     *
     * @return string
     */
    public function noCase($value, array $options = []): string
    {
        $options += [
            'delimiter'       => ' ',
            'splitRegexp'     => ['/([a-z0-9])([A-Z])/', '/([A-Z])([A-Z][a-z])/'],
            'stripRegexp'     => '/[^a-zA-Z0-9]+/i',
            'separateNumbers' => false,
        ];

        $splitRegexp = $options['splitRegexp'];

        if ($options['separateNumbers']) {
            $splitRegexp = [...$options['splitRegexp'], '/([0-9])([A-Za-z])/', '/([A-Za-z])([0-9])/'];
        }

        $result = preg_replace(
            $options['stripRegexp'],
            $options['delimiter'],
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

        // Transform each token independently.
        return implode(
            $options['delimiter'],
            array_map(
                'mb_strtolower',
                mb_split(
                    ' ',
                    UTF8::str_slice($result, $start, $end)
                )
            )
        );
    }

    /**
     * Transform into a string with the separator denoted by the next word capitalized.
     *
     * @param string $string
     * @param array  $options
     *
     * @return string
     */
    public function camelCase(string $string, array $options = []): string
    {
        return UTF8::lcfirst(
            $this->pascalCase($string, $options)
        );
    }

    /**
     * Transform into a space separated string with each word capitalized.
     *
     * @param string $string
     *
     * @return string
     */
    public function capitalCase(string $string): string
    {
        return preg_replace_callback(
            '/^.| ./u',
            function (array $matches) {
                return mb_strtoupper($matches[0]);
            },
            $this->noCase($string)
        );
    }

    /**
     * Transform into upper case string with an underscore between words.
     *
     * @param string $string
     *
     * @return string
     */
    public function constantCase(string $string): string
    {
        return mb_strtoupper($this->snakeCase($string));
    }

    /**
     * Transform into a lower case string with a period between words.
     *
     * @param string $string
     * @param array  $options
     *
     * @return string
     */
    public function dotCase(string $string, array $options = []): string
    {
        return $this->noCase(
            $string,
            $options += [
                'delimiter' => '.',
            ]
        );
    }

    /**
     * Transform into a dash separated string of capitalized words.
     *
     * @param string $string
     *
     * @return string
     */
    public function headerCase(string $string): string
    {
        return preg_replace_callback(
            '/^.|-./u',
            function (array $matches) {
                return mb_strtoupper($matches[0]);
            },
            $this->noCase(
                $string,
                $options = [
                    'delimiter' => '-',
                ]
            )
        );
    }

    /**
     * Transform into a string of capitalized words without separators.
     *
     * @param string $string
     * @param array  $options
     *
     * @return string
     */
    public function pascalCase(string $string, array $options = []): string
    {
        $value = UTF8::ucwords(
            str_replace(
                ['-', '_'],
                ' ',
                $this->noCase($string, $options)
            )
        );

        return str_replace(' ', '', $value);
    }

    /**
     * Transform into a lower case string with slashes between words.
     *
     * @param string $string
     * @param array  $options
     *
     * @return string
     */
    public function pathCase(string $string, array $options = []): string
    {
        return $this->noCase(
            $string,
            $options += [
                'delimiter' => '/',
            ]
        );
    }

    /**
     * Transform into a lower case with spaces between words, then capitalize the string.
     *
     * @param string $string
     *
     * @return string
     */
    public function sentenceCase(string $string): string
    {
        return UTF8::ucfirst($this->noCase($string));
    }

    /**
     * Transform into a lower case string with underscores between words.
     *
     * @param string $string
     * @param array  $options
     *
     * @return string
     */
    public function snakeCase(string $string, array $options = []): string
    {
        $stripRegexp = '/(?!^_*)[^a-zA-Z0-9]+/i';

        return $this->noCase(
            $string,
            $options += [
                'delimiter'   => '_',
                'stripRegexp' => $stripRegexp,
            ]
        );
    }

    /**
     * Transform into a lower cased string with dashes between words.
     *
     * @param string $string
     * @param array  $options
     *
     * @return string
     */
    public function spinalCase(string $string, array $options = []): string
    {
        return $this->noCase(
            $string,
            $options += [
                'delimiter' => '-',
            ],
        );
    }

    /**
     * Transform a string by swapping every character from upper to lower case, or lower
     * to upper case.
     *
     * @param string $string
     *
     * @return string
     */
    public function swapCase(string $string): string
    {
        if ($string === '') {
            return '';
        }

        return (string) (mb_strtolower($string) ^ mb_strtoupper($string) ^ $string);
    }

    /**
     * Transform a string into title case following English rules.
     *
     * Reference
     * - https://github.com/Kroc/PHPtitleCase
     * - https://github.com/blakeembrey/change-case (packages/title-case)
     *
     * @param string $string
     *
     * @return string
     */
    public function titleCase(string $string): string
    {
        $smallWords = '/^(a(nd?|s|t)?|b(ut|y)|en|for|i[fn]|o[fnr]|only|over|tha[tn]|t(he|o)|up|upon|vs?\.?|via)[ \-]/i';

        // find each word (including punctuation attached)
        preg_match_all('/[\w\p{L}&`\'‘’"“\.@:\/\{\(\[<>_]+-? */u', $string, $match_1, PREG_OFFSET_CAPTURE);

        foreach ($match_1[0] as $match_2) {
            [$match, $index] = $match_2;

            // Correct offsets for multi-byte characters (`PREG_OFFSET_CAPTURE` returns
            // byte-offset). We fix this by recounting the text before the offset using
            // multi-byte aware `strlen`
            $index = mb_strlen(substr($string, 0, $index));

            $wordLC = $index > 0
                      && mb_substr($string, max(0, $index - 2), 1) !== ':'
                      && preg_match($smallWords, $match);
            $wrappers = preg_match('/[\'"_{(\[‘“]/u', mb_substr($string, max(0, $index - 1), 3));
            $lowerC = preg_match('/[\])}]/', mb_substr($string, max(0, $index - 1), 3))
                      || preg_match('/[A-Z]+|&|\w+[._]\w+/u', mb_substr($match, 1, mb_strlen($match) - 1));

            // Words that must always be lowercase are found (never in the first word, and
            // never if they start with a colon).
            if ($wordLC) {
                // ..and convert them to lowercase
                $match = mb_strtolower($match);

            // Brackets and other wrappers were found
            } elseif ($wrappers) {
                // convert first letter within wrapper to uppercase
                $match = mb_substr($match, 0, 1).
                         mb_strtoupper(mb_substr($match, 1, 1)).
                         mb_substr($match, 2, mb_strlen($match) - 2);

            // Do not uppercase these cases
            } elseif ($lowerC) {
                continue;
            } else {
                // if all else fails, then no more fringe-cases; uppercase the word
                $match = mb_strtoupper(mb_substr($match, 0, 1)).
                         mb_substr($match, 1, mb_strlen($match));
            }

            // Resplice the title with the change
            $string = mb_substr($string, 0, $index).$match.
                      mb_substr($string, $index + mb_strlen($match), mb_strlen($string));
        }

        return $string;
    }
}
