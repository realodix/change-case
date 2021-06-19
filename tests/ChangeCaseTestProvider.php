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
            ['spinalCase', 'spinal-case'],
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
            ['Spinal Case', 'spinal-case'],
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
            ['SPINAL_CASE', 'spinal-case'],
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
            ['spinal.case', 'spinal-case'],
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

    public function pascalCaseProvider()
    {
        return [
            ['String', 'string'],
            ['CamelCase', 'camelCase'],
            ['CamelCase', 'CAMELCase'],
            ['CapitalCase', 'Capital Case'],
            ['ConstantCase', 'CONSTANT_CASE'],
            ['DotCase', 'dot.case'],
            ['SpinalCase', 'spinal-case'],
            ['PathCase', 'path/case'],
            ['SnakeCase', 'snake_case'],
            ['Version1210', 'version 1.2.10'],
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
            ['spinal/case', 'spinal-case'],
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
            ['Spinal case', 'spinal-case'],
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
            ['spinal_case', 'spinal-case'],
            ['version_1_2_10', 'version 1.2.10'],
            ['version_1_21_0', 'version 1.21.0'],
            ['__typename', '__typename'],
        ];
    }

    public function spinalCaseProvider()
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
            /*
             * https://grammar.yourdictionary.com/capitalization/rules-for-capitalization-in-titles.html
             */
            // Capitalize the First and Last Word
            ['how to land your dream job', 'How to Land Your Dream Job'],
            ['of mice and men', 'Of Mice and Men'],
            ['the cat in the hat', 'The Cat in the Hat'],

            // Capitalize Nouns and Pronouns
            ['visiting beautiful ruins', 'Visiting Beautiful Ruins'],
            ['as she ran away', 'As She Ran Away'],
            ['little house on the prairie', 'Little House on the Prairie'],
            ['for whom the bell tolls', 'For Whom the Bell Tolls'],

            // Capitalize Verbs and Helping Verbs
            ['to kill a mockingbird', 'To Kill a Mockingbird'],
            ['the sun also rises', 'The Sun Also Rises'],
            ['their eyes were watching god', 'Their Eyes Were Watching God'],
            ['tender is the night', 'Tender Is the Night'],

            // Capitalize Adjectives and Adverbs
            ['all quiet on the western', 'All Quiet on the Western'],
            ['the wind-up bird chronicle', 'The Wind-Up Bird Chronicle'],
            ['she quietly waits', 'She Quietly Waits'],
            ['the poky little puppy', 'The Poky Little Puppy'],

            // Do Not Capitalize Short Prepositions
            ['one year in paris', 'One Year in Paris'],
            ['the book of disquiet', 'The Book of Disquiet'],
            ['a house for mr. biswas', 'A House for Mr. Biswas'],

            // Do Not Capitalize Articles
            ['through the looking glass', 'Through the Looking Glass'],
            ['the portrait of a lady', 'The Portrait of a Lady'],
            ['the sense of an ending', 'The Sense of an Ending'],

            // Do Not Capitalize Short Coordinating Conjunctions
            [
                'do not capitalize ("and, but, or, for, nor") short coordinating conjunctions',
                'Do Not Capitalize ("and, but, or, for, nor") Short Coordinating Conjunctions',
            ],

            /*
             * https://github.com/blakeembrey/change-case/blob/master/packages/title-case/src/index.spec.ts/
             */
            ['one. two.', 'One. Two.'],
            ['small word ends on', 'Small Word Ends On'],
            ['we keep NASA capitalized', 'We Keep NASA Capitalized'],
            ['pass camelCase through', 'Pass camelCase Through'],
            ['follow step-by-step instructions', 'Follow Step-by-Step Instructions'],
            ['your hair[cut] looks (nice)', 'Your Hair[cut] Looks (Nice)'],
            ['leave Q&A unscathed', 'Leave Q&A Unscathed'],
            [
                'piña colada while you listen to ænima',
                'Piña Colada While You Listen to Ænima',
            ],
            ['start title – end title', 'Start Title – End Title'],
            ['start title–end title', 'Start Title–End Title'],
            ['start title — end title', 'Start Title — End Title'],
            ['start title—end title', 'Start Title—End Title'],
            ['start title - end title', 'Start Title - End Title'],
            ["don't break", "Don't Break"],
            ['"double quotes"', '"Double Quotes"'],
            ['double quotes "inner" word', 'Double Quotes "Inner" Word'],
            ['fancy double quotes “inner” word', 'Fancy Double Quotes “Inner” Word'],
            ['have you read “The Lottery”?', 'Have You Read “The Lottery”?'],
            ['one: two', 'One: Two'],
            ['one two: three four', 'One Two: Three Four'],
            ['one two: "Three Four"', 'One Two: "Three Four"'],
            ['email email@example.com address', 'Email email@example.com Address'],
            [
                'you have an https://example.com/ title',
                'You Have an https://example.com/ Title',
            ],
            ['_underscores around words_', '_Underscores Around Words_'],
            ['*asterisks around words*', '*Asterisks Around Words*'],
            ['this vs. that', 'This vs. That'],
            ['this vs that', 'This vs That'],
            ['this v. that', 'This v. That'],
            ['this v that', 'This v That'],
            [
                'Scott Moritz and TheStreet.com’s million iPhone la-la land',
                'Scott Moritz and TheStreet.com’s Million iPhone La-La Land',
            ],
            [
                'Notes and observations regarding Apple’s announcements from ‘The Beat Goes On’ special event',
                'Notes and Observations Regarding Apple’s Announcements From ‘The Beat Goes On’ Special Event',
            ],
            [
                'the quick brown fox jumps over the lazy dog',
                'The Quick Brown Fox Jumps over the Lazy Dog',
            ],
            ['newcastle upon tyne', 'Newcastle upon Tyne'],
            ['newcastle *upon* tyne', 'Newcastle *upon* Tyne'],
        ];
    }
}
