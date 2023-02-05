<?php

namespace Realodix\ChangeCase\Test;

trait ChangeCaseTestProvider
{
    public function noCaseProvider()
    {
        return [
            // Single words.
            ['test', 'test'],
            ['TEST', 'test'],

            // Camel case.
            ['testString', 'test string'],
            ['testString123', 'test string123'],
            ['testString_1_2_3', 'test string 1 2 3'],
            ['x_256', 'x 256'],
            ['anHTMLTag', 'an html tag'],
            ['ID123String', 'id123 string'],
            ['Id123String', 'id123 string'],
            ['foo bar123', 'foo bar123'],
            ['a1bStar', 'a1b star'],

            // Constant case.
            ['CONSTANT_CASE ', 'constant case'],
            ['CONST123_FOO', 'const123 foo'],

            // Random cases.
            ['FOO_bar', 'foo bar'],
            ['XMLHttpRequest', 'xml http request'],
            ['IQueryAArgs', 'i query a args'],

            // Non-alphanumeric separators.
            ['dot.case', 'dot case'],
            ['path/case', 'path case'],
            ['snake_case', 'snake case'],
            ['snake_case123', 'snake case123'],
            ['snake_case_123', 'snake case 123'],

            // Punctuation.
            ['"quotes"', 'quotes'],

            // Space between number parts.
            ['version 0.45.0', 'version 0 45 0'],
            ['version 0..78..9', 'version 0 78 9'],
            ['version 4_99/4', 'version 4 99 4'],

            // Whitespace.
            ['  test  ', 'test'],

            // Number string input.
            ['something_2014_other', 'something 2014 other'],

            // https://github.com/blakeembrey/change-case/issues/21
            ['amazon s3 data', 'amazon s3 data'],
            ['foo_13_bar', 'foo 13 bar'],
        ];
    }

    public function camelCaseProvider()
    {
        return [
            ['string', 'string'],
            ['camelCase', 'CAMELCase'],
            ['capitalCase', 'Capital Case'],
            ['constantCase', 'CONSTANT_CASE'],
            ['dotCase', 'dot.case'],
            ['kebabCase', 'kebab-case'],
            ['pascalCase', 'PascalCase'],
            ['pathCase', 'path/case'],
            ['snakeCase', 'snake_case'],
            ['version1210', 'version 1.2.10'],
        ];
    }

    public function capitalCaseProvider()
    {
        return [
            ['String', 'string'],
            ['Camel Case', 'camelCase'],
            ['Camel Case', 'CAMELCase'],
            ['Capital Case', 'Capital Case'],
            ['Constant Case', 'CONSTANT_CASE'],
            ['Dot Case', 'dot.case'],
            ['Kebab Case', 'kebab-case'],
            ['Pascal Case', 'PascalCase'],
            ['Path Case', 'path/case'],
            ['Snake Case', 'snake_case'],
            ['Version 1 2 10', 'version 1.2.10'],
        ];
    }

    public function constantCaseProvider()
    {
        return [
            ['STRING', 'string'],
            ['CAMEL_CASE', 'camelCase'],
            ['CAMEL_CASE', 'CAMELCase'],
            ['CAPITAL_CASE', 'Capital Case'],
            ['DOT_CASE', 'dot.case'],
            ['KEBAB_CASE', 'kebab-case'],
            ['PASCAL_CASE', 'PascalCase'],
            ['PATH_CASE', 'path/case'],
            ['SNAKE_CASE', 'snake_case'],
            ['VERSION_1_2_10', 'version 1.2.10'],
        ];
    }

    public function dotCaseProvider()
    {
        return [
            ['string', 'string'],
            ['camel.case', 'camelCase'],
            ['camel.case', 'CAMELCase'],
            ['capital.case', 'Capital Case'],
            ['constant.case', 'CONSTANT_CASE'],
            ['kebab.case', 'kebab-case'],
            ['pascal.case', 'PascalCase'],
            ['path.case', 'path/case'],
            ['snake.case', 'snake_case'],
            ['version.1.2.10', 'version 1.2.10'],
        ];
    }

    public function headerCaseProvider()
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

    public function headlineCaseProvider()
    {
        return [
            ['', ''],
            ['Test', 'test'],
            ['Test String', 'test string'],
            ['Test String', 'Test String'],
            ['Test V2', 'TestV2'],
            ['Version 1.2.10', 'version 1.2.10'],
            ['Version 1.21.0', 'version 1.21.0'],

            ['Jefferson Costella', 'jefferson costella'],
            ['Jefferson Costella', 'jefFErson coSTella'],
            ['Jefferson Costella Uses Laravel', 'jefferson_costella uses-_Laravel'],
            ['Jefferson Costella Uses Laravel', 'jefferson_costella uses__Laravel'],

            ['Laravel P H P Framework', 'laravel_p_h_p_framework'],
            ['Laravel P H P Framework', 'laravel _p _h _p _framework'],
            ['Laravel Php Framework', 'laravel_php_framework'],
            ['Laravel Ph P Framework', 'laravel-phP-framework'],
            ['Laravel Php Framework', 'laravel  -_-  php   -_-   framework   '],

            ['Foo Bar', 'fooBar'],
            ['Foo Bar', 'foo_bar'],
            ['Foo Bar Baz', 'foo-barBaz'],
            ['Foo Bar Baz', 'foo-bar_baz'],

            ['Öffentliche Überraschungen', 'öffentliche-überraschungen'],
            ['Öffentliche Überraschungen', '-_öffentliche_überraschungen_-'],
            ['Öffentliche Überraschungen', '-öffentliche überraschungen'],

            ['Sind Öde Und So', 'sindÖdeUndSo'],

            ['Orwell 1984', 'orwell 1984'],
            ['Orwell 1984', 'orwell   1984'],
            ['Orwell 1984', '-orwell-1984 -'],
            ['Orwell 1984', ' orwell_- 1984 '],

            ['Iñt Ërn Âti Ônà Liz Æti Øn', 'iñt ërn âTi ônà liz æti øn'],
        ];
    }

    public function kebabCaseProvider()
    {
        return [
            ['string', 'string'],
            ['camel-case', 'camelCase'],
            ['camel-case', 'CAMELCase'],
            ['capital-case', 'Capital Case'],
            ['constant-case', 'CONSTANT_CASE'],
            ['dot-case', 'dot.case'],
            ['path-case', 'path/case'],
            ['snake-case', 'snake_case'],
            ['version-1-2-10', 'version 1.2.10'],
        ];
    }

    public function pascalCaseProvider()
    {
        return [
            ['String', 'string'],
            ['CamelCase', 'camelCase'],
            ['CamelCase', 'CAMELCase'],
            ['CapitalCase', 'Capital Case'],
            ['ConstantCase', 'CONSTANT_CASE'],
            ['DotCase', 'dot.case'],
            ['KebabCase', 'kebab-case'],
            ['PathCase', 'path/case'],
            ['SnakeCase', 'snake_case'],
            ['Version1210', 'version 1.2.10'],
            ['IñtËrnÂTiÔnàLizÆtiØn', 'iñt ërn âTi ônà liz æti øn'],
            // https://github.com/blakeembrey/change-case/issues/291
            ['İstanbul', 'İSTANBUL'],
            ['İzmir', 'İZMİR'],
        ];
    }

    public function pathCaseProvider()
    {
        return [
            ['string', 'string'],
            ['camel/case', 'camelCase'],
            ['camel/case', 'CAMELCase'],
            ['capital/case', 'Capital Case'],
            ['constant/case', 'CONSTANT_CASE'],
            ['dot/case', 'dot.case'],
            ['kebab/case', 'kebab-case'],
            ['pascal/case', 'PascalCase'],
            ['snake/case', 'snake_case'],
            ['version/1/2/10', 'version 1.2.10'],
        ];
    }

    public function sentenceCaseProvider()
    {
        return [
            ['String', 'string'],
            ['Camel case', 'camelCase'],
            ['Camel case', 'CAMELCase'],
            ['Capital case', 'Capital Case'],
            ['Constant case', 'CONSTANT_CASE'],
            ['Dot case', 'dot.case'],
            ['Kebab case', 'kebab-case'],
            ['Pascal case', 'PascalCase'],
            ['Path case', 'path/case'],
            ['Snake case', 'snake_case'],
            ['Version 1 2 10', 'version 1.2.10'],
        ];
    }

    public function snakeCaseProvider()
    {
        return [
            ['string', 'string'],
            ['camel_case', 'camelCase'],
            ['camel_case', 'CAMELCase'],
            ['capital_case', 'Capital Case'],
            ['constant_case', 'CONSTANT_CASE'],
            ['dot_case', 'dot.case'],
            ['path_case', 'path/case'],
            ['kebab_case', 'kebab-case'],
            ['version_1_2_10', 'version 1.2.10'],
            ['version_1_21_0', 'version 1.21.0'],
            ['__typename', '__typename'],
        ];
    }

    public function swapCaseProvider()
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

    public function titleCaseProvider()
    {
        return [
            ['', ''],
            ['Test', 'test'],
            ['Test String', 'test string'],
            ['Test String', 'Test String'],
            ['Testv2', 'TestV2'],
            ['Version 1.2.10', 'version 1.2.10'],
            ['Version 1.21.0', 'version 1.21.0'],

            ['Jefferson Costella', 'jefferson costella'],
            ['Jefferson Costella', 'jefFErson coSTella'],
            ['Jefferson_Costella Uses-_Laravel', 'jefferson_costella uses-_Laravel'],
            ['Jefferson_Costella Uses__Laravel', 'jefferson_costella uses__Laravel'],

            ['Laravel_P_H_P_Framework', 'laravel_p_h_p_framework'],
            ['Laravel _P _H _P _Framework', 'laravel _p _h _p _framework'],
            ['Laravel_Php_Framework', 'laravel_php_framework'],
            ['Laravel-Php-Framework', 'laravel-phP-framework'],
            ['Laravel  -_-  Php   -_-   Framework   ', 'laravel  -_-  php   -_-   framework   '],

            ['Foobar', 'fooBar'],
            ['Foo_Bar', 'foo_bar'],
            ['Foo-Barbaz', 'foo-barBaz'],
            ['Foo-Bar_Baz', 'foo-bar_baz'],

            ['Öffentliche-Überraschungen', 'öffentliche-überraschungen'],
            ['-_Öffentliche_Überraschungen_-', '-_öffentliche_überraschungen_-'],
            ['-Öffentliche Überraschungen', '-öffentliche überraschungen'],

            ['Sindödeundso', 'sindÖdeUndSo'],

            ['Orwell 1984', 'orwell 1984'],
            ['Orwell   1984', 'orwell   1984'],
            ['-Orwell-1984 -', '-orwell-1984 -'],
            [' Orwell_- 1984 ', ' orwell_- 1984 '],

            ['Iñt Ërn Âti Ônà Liz Æti Øn', 'iñt ërn âTi ônà liz æti øn'],
        ];
    }
}
