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
    public function noCase(string $value, array $opt = []): string
    {
        // Support camel case ("camelCase" -> "camel Case" and "CAMELCase" -> "CAMEL Case")
        $splitRegexp = ['/([a-z0-9])([A-Z])/', '/([A-Z])([A-Z][a-z])/'];
        // Regex to split numbers ("13test" -> "13 test")
        $splitNumberRegexp = array_merge($splitRegexp, ['/([0-9])([a-zA-Z])/', '/([a-zA-Z])([0-9])/']);
        // Remove all non-word characters
        $stripRegexp = '/[^a-zA-Z0-9]+/i';

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
        $end = strlen($result);
        while (UTF8::char_at($result, $start) === ' ') {
            $start++;
        }
        while (UTF8::char_at($result, $end - 1) === ' ') {
            $end--;
        }

        $slice = UTF8::str_slice($result, $start, $end);
        $split = explode(' ', $slice);
        $toLowerCase = array_map('strtolower', $split);
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
    public function camelCase(string $string, array $opt = []): string
    {
        return UTF8::lcfirst($this->pascalCase($string, $opt));
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
                return strtoupper($matches[0]);
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
        return strtoupper($this->snakeCase($string));
    }

    /**
     * Transform into a lower case string with a period between words.
     *
     * @param string $string
     * @param array  $opt
     *
     * @return string
     */
    public function dotCase(string $string, array $opt = []): string
    {
        return $this->noCase($string, $opt += ['delimiter' => '.']);
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
                return strtoupper($matches[0]);
            },
            $this->noCase($string, ['delimiter' => '-'])
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
    public function pascalCase(string $string, array $opt = []): string
    {
        $value = UTF8::ucwords($this->noCase($string, $opt));

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
    public function pathCase(string $string, array $opt = []): string
    {
        return $this->noCase($string, $opt += ['delimiter' => '/']);
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
     * @param array  $opt
     *
     * @return string
     */
    public function snakeCase(string $string, array $opt = []): string
    {
        $stripRegexp = '/(?!^_*)[^a-zA-Z0-9]+/i';

        return $this->noCase(
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
    public function spinalCase(string $string, array $opt = []): string
    {
        return $this->noCase($string, $opt += ['delimiter' => '-']);
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
        return strtolower($string) ^ strtoupper($string) ^ $string;
    }

    /**
     * Transform a string into title case following English rules.
     *
     * @param string $string
     * @param array  $ignore An array of words not to capitalize.
     *
     * @return string
     */
    public function titleCase(string $string, array $ignore = []): string
    {
        $smallWords = ['nor', 'over', 'upon'];

        return UTF8::str_titleize_for_humans($string, array_merge($smallWords, $ignore));
    }
}
