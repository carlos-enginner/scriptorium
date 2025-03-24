<?php

declare(strict_types=1);

namespace Tests\Unit;

use App\Helper\TsQuery;
use PHPUnit\Framework\TestCase;

/**
 * @coversDefaultClass \App\Helper\TsQuery
 */
class TsQueryTest extends TestCase
{
    /**
     * @covers ::tokenizer
     */
    public function test_tokenizer_happy_path()
    {
        $input = "john doe";
        $expectedOutput = "john&doe";

        $actualOutput = TsQuery::tokenizer($input);

        $this->assertEquals($expectedOutput, $actualOutput);
    }

    /**
     * @covers ::tokenizer
     */
    public function test_tokenizer_edge_case_empty_string()
    {
        $input = "";
        $expectedOutput = "";

        $actualOutput = TsQuery::tokenizer($input);

        $this->assertEquals($expectedOutput, $actualOutput);
    }

    /**
     * @covers ::tokenizer
     */
    public function test_tokenizer_edge_case_multiple_spaces()
    {
        $input = "john  doe";
        $expectedOutput = "john&doe";

        $actualOutput = TsQuery::tokenizer($input);

        $this->assertEquals($expectedOutput, $actualOutput);
    }

    /**
     * @covers ::tokenizer
     */
    public function test_tokenizer_edge_case_single_word()
    {
        $input = "john";
        $expectedOutput = "john";

        $actualOutput = TsQuery::tokenizer($input);

        $this->assertEquals($expectedOutput, $actualOutput);
    }
}
