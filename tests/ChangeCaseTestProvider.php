<?php

namespace Realodix\ChangeCase\Test;

trait ChangeCaseTestProvider
{
    public static function noCaseProvider()
    {
        return [
            // Single words.
            ['test', 'test'],
            ['test', 'TEST'],

            // Camel case.
            ['test string', 'testString'],
            ['test string123', 'testString123'],
            ['test string 1 2 3', 'testString_1_2_3'],
            ['x 256', 'x_256'],
            ['an html tag', 'anHTMLTag'],
            ['id123 string', 'ID123String'],
            ['id123 string', 'Id123String'],
            ['foo bar123', 'foo bar123'],
            ['a1b star', 'a1bStar'],

            // Constant case.
            ['constant case', 'CONSTANT_CASE '],
            ['const123 foo', 'CONST123_FOO'],

            // Random cases.
            ['foo bar', 'FOO_bar'],
            ['xml http request', 'XMLHttpRequest'],
            ['i query a args', 'IQueryAArgs'],

            // Non-alphanumeric separators.
            ['dot case', 'dot.case'],
            ['path case', 'path/case'],
            ['snake case', 'snake_case'],
            ['snake case123', 'snake_case123'],
            ['snake case 123', 'snake_case_123'],

            // Punctuation.
            ['quotes', '"quotes"'],

            // Space between number parts.
            ['version 0 45 0', 'version 0.45.0'],
            ['version 0 78 9', 'version 0..78..9'],
            ['version 4 99 4', 'version 4_99/4'],

            // Whitespace.
            ['test', '  test  '],

            // Number string input.
            ['something 2014 other', 'something_2014_other'],

            // https://github.com/blakeembrey/change-case/issues/21
            ['amazon s3 data', 'amazon s3 data'],
            ['foo 13 bar', 'foo_13_bar'],

            // Clean up excess delimiters
            ['foo', '      foo           '],
            ['foo bar', '      foo      bar     '],
            ['constant case', 'CONSTANT________CASE'],
            ['dot case', 'dot.case'],
            ['kebab case', 'kebab--------------------case'],
            ['snake case', 'snake___________case'],
        ];
    }

    public static function camelCaseProvider()
    {
        return [
            ['string', 'string'],
            ['camelCase', 'CAMELCase'],
            ['constantCase', 'CONSTANT_CASE'],
            ['dotCase', 'dot.case'],
            ['headlineCase', 'Headline Case'],
            ['kebabCase', 'kebab-case'],
            ['pascalCase', 'PascalCase'],
            ['pathCase', 'path/case'],
            ['snakeCase', 'snake_case'],
            ['version1210', 'version 1.2.10'],
        ];
    }

    public static function constantCaseProvider()
    {
        return [
            ['STRING', 'string'],
            ['CAMEL_CASE', 'camelCase'],
            ['CAMEL_CASE', 'CAMELCase'],
            ['DOT_CASE', 'dot.case'],
            ['HEADLINE_CASE', 'Headline Case'],
            ['KEBAB_CASE', 'kebab-case'],
            ['PASCAL_CASE', 'PascalCase'],
            ['PATH_CASE', 'path/case'],
            ['SNAKE_CASE', 'snake_case'],
            ['VERSION_1_2_10', 'version 1.2.10'],
        ];
    }

    public static function dotCaseProvider()
    {
        return [
            ['string', 'string'],
            ['camel.case', 'camelCase'],
            ['camel.case', 'CAMELCase'],
            ['constant.case', 'CONSTANT_CASE'],
            ['headline.case', 'Headline Case'],
            ['kebab.case', 'kebab-case'],
            ['pascal.case', 'PascalCase'],
            ['path.case', 'path/case'],
            ['snake.case', 'snake_case'],
            ['version.1.2.10', 'version 1.2.10'],
        ];
    }

    public static function headerCaseProvider()
    {
        return [
            ['', ''],
            ['Test', 'test'],
            ['Test-String', 'test string'],
            ['Test-String', 'Test String'],
            ['Test-V2', 'TestV2'],
            ['Version-1-2-10', 'version 1.2.10'],
            ['Version-1-21-0', 'version 1.21.0'],
        ];
    }

    public static function headlineCaseProvider()
    {
        return [
            ['', ''],
            ['Test', 'test'],
            ['Test String', 'test string'],
            ['Test String', 'Test String'],
            ['Test V2', 'TestV2'],

            ['Laravel P H P Framework', 'laravel_p_h_p_framework'],
            ['Laravel P H P Framework', 'laravel _p _h _p _framework'],
            ['Laravel Php Framework', 'laravel_php_framework'],
            ['Laravel Ph P Framework', 'laravel-phP-framework'],
            ['Laravel Php Framework', 'laravel  -_-  php   -_-   framework   '],

            ['Ärmere Öffentliche Überraschungen Sind Öde', 'ärmere-öffentliche-überraschungen-sind-öde'],
            ['Sind Öde Und So', 'sindÖdeUndSo'],

            ['String', 'string'],
            ['Camel Case', 'camelCase'],
            ['C A M E L Case', 'CAMELCase'], // Error
            ['Dot.case', 'dot.case'],
            ['Headline Case', 'Headline Case'],
            ['Header Case', 'Header-Case'],
            ['Kebab Case', 'kebab-case'],
            ['Pascal Case', 'PascalCase'],
            ['Path/Case', 'path/case'],
            ['Snake Case', 'snake_case'],

            ['Dr. (H.c.) Ir. H. Soekarno', 'dr. (h.c.) ir. h. soekarno'],
            ['Prof. Dr. Ing. Ir. H. Bacharuddin Jusuf Habibie', 'prof. dr.-ing. ir. h. bacharuddin jusuf habibie'],
            ['Ir. H. Joko Widodo', 'IR. H. JOKO WIDODO'],

            ['Black Panther: Wakanda Forever (2022)', 'Black Panther: Wakanda Forever (2022)'],
            ['Adobe Lightroom Classic 2022 V11.4.1', 'Adobe Lightroom Classic 2022 v11.4.1'],
            ['Original Windows 10 Pro + Office 365 + Adobe', 'Original Windows 10 Pro + Office 365 + Adobe'],
        ];
    }

    public static function kebabCaseProvider()
    {
        return [
            ['string', 'string'],
            ['camel-case', 'camelCase'],
            ['camel-case', 'CAMELCase'],
            ['constant-case', 'CONSTANT_CASE'],
            ['dot-case', 'dot.case'],
            ['headline-case', 'Headline Case'],
            ['path-case', 'path/case'],
            ['snake-case', 'snake_case'],
            ['version-1-2-10', 'version 1.2.10'],
        ];
    }

    public static function pascalCaseProvider()
    {
        return [
            ['String', 'string'],
            ['CamelCase', 'camelCase'],
            ['CamelCase', 'CAMELCase'],
            ['ConstantCase', 'CONSTANT_CASE'],
            ['DotCase', 'dot.case'],
            ['HeadlineCase', 'Headline Case'],
            ['KebabCase', 'kebab-case'],
            ['PathCase', 'path/case'],
            ['SnakeCase', 'snake_case'],
            ['Version1210', 'version 1.2.10'],
            ['IñtËrnÂTiÔnàLizÆtiØn', 'iñt ërn âTi ônà liz æti øn'],
            // https://github.com/blakeembrey/change-case/issues/291
            ['İzmi̇r', 'İZMİR'], // Not İzmir
            ['İstanbul', 'İSTANBUL'], // Not İstanbul
        ];
    }

    public static function pathCaseProvider()
    {
        return [
            ['string', 'string'],
            ['camel/case', 'camelCase'],
            ['camel/case', 'CAMELCase'],
            ['constant/case', 'CONSTANT_CASE'],
            ['dot/case', 'dot.case'],
            ['headline/case', 'Headline Case'],
            ['kebab/case', 'kebab-case'],
            ['pascal/case', 'PascalCase'],
            ['snake/case', 'snake_case'],
            ['version/1/2/10', 'version 1.2.10'],
        ];
    }

    public static function sentenceCaseProvider()
    {
        return [
            ['', ''],
            ['Test', 'test'],
            ['Test string', 'test string'],
            ['Test string', 'Test String'],
            ['Test v2', 'TestV2'],

            ['Laravel p h p framework', 'laravel_p_h_p_framework'],
            ['Laravel p h p framework', 'laravel _p _h _p _framework'],
            ['Laravel php framework', 'laravel_php_framework'],
            ['Laravel ph p framework', 'laravel-phP-framework'],
            ['Laravel php framework', 'laravel  -_-  php   -_-   framework   '],

            ['Ärmere öffentliche überraschungen sind öde', 'ärmere-öffentliche-überraschungen-sind-öde'],
            ['Sind öde und so', 'sindÖdeUndSo'],

            ['String', 'string'],
            ['Camel case', 'camelCase'],
            ['Camel case', 'CAMELCase'],
            ['Constant case', 'CONSTANT_CASE'],
            ['Dot case', 'dot.case'],
            ['Headline case', 'Headline Case'],
            ['Kebab case', 'kebab-case'],
            ['Pascal case', 'PascalCase'],
            ['Path case', 'path/case'],
            ['Snake case', 'snake_case'],

            ['Black panther wakanda forever 2022', 'Black Panther: Wakanda Forever (2022)'],
            ['Adobe lightroom classic 2022 v11 4 1', 'Adobe Lightroom Classic 2022 v11.4.1'],
            ['Original windows 10 pro office 365 adobe', 'Original Windows 10 Pro + Office 365 + Adobe'],
        ];
    }

    public static function snakeCaseProvider()
    {
        return [
            ['string', 'string'],
            ['camel_case', 'camelCase'],
            ['camel_case', 'CAMELCase'],
            ['constant_case', 'CONSTANT_CASE'],
            ['dot_case', 'dot.case'],
            ['headline_case', 'Headline Case'],
            ['path_case', 'path/case'],
            ['kebab_case', 'kebab-case'],
            ['version_1_2_10', 'version 1.2.10'],
            ['version_1_21_0', 'version 1.21.0'],
            ['__typename', '__typename'],
        ];
    }

    public static function swapCaseProvider()
    {
        return [
            ['', ''],
            ['test', 'TEST'],
            ['test string', 'TEST STRING'],
            ['Test String', 'tEST sTRING'],
            ['TestV2', 'tESTv2'],
            ['sWaP cAsE', 'SwAp CaSe'],
        ];
    }
}
