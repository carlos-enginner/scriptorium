<?php

declare(strict_types=1);
/**
 * This file is part of Hyperf.
 *
 * @link     https://www.hyperf.io
 * @document https://hyperf.wiki
 * @contact  group@hyperf.io
 * @license  https://github.com/hyperf/hyperf/blob/master/LICENSE
 */

namespace Tests\Unit;

use App\Helper\TsQuery;
use PHPUnit\Framework\TestCase;

/**
 * @coversDefaultClass \App\Helper\TsQuery
 * @internal
 */
class TsQueryTest extends TestCase
{
    /**
     * @covers ::tokenizer
     */
    public function testTokenizerHappyPath()
    {
        $input = 'john doe';
        $expectedOutput = 'john&doe';

        $actualOutput = TsQuery::tokenizer($input);

        $this->assertEquals($expectedOutput, $actualOutput);
    }

    /**
     * @covers ::tokenizer
     */
    public function testTokenizerEdgeCaseEmptyString()
    {
        $input = '';
        $expectedOutput = '';

        $actualOutput = TsQuery::tokenizer($input);

        $this->assertEquals($expectedOutput, $actualOutput);
    }

    /**
     * @covers ::tokenizer
     */
    public function testTokenizerEdgeCaseMultipleSpaces()
    {
        $input = 'john  doe';
        $expectedOutput = 'john&doe';

        $actualOutput = TsQuery::tokenizer($input);

        $this->assertEquals($expectedOutput, $actualOutput);
    }

    /**
     * @covers ::tokenizer
     */
    public function testTokenizerEdgeCaseSingleWord()
    {
        $input = 'john';
        $expectedOutput = 'john';

        $actualOutput = TsQuery::tokenizer($input);

        $this->assertEquals($expectedOutput, $actualOutput);
    }
}
