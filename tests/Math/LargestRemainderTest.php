<?php

namespace Aeq\LargestRemainder\Math;

use PHPUnit\Framework\TestCase;

class LargestRemainderTest extends TestCase
{
    /**
     * @test
     */
    public function shouldRoundIfUnder100()
    {
        $numbers = [18, 12, 24, 15, 30];

        $lr = new LargestRemainder($numbers);

        $this->assertEquals(100, array_sum($lr->round()));
    }

    /**
     * @test
     */
    public function shouldRoundIfOver100()
    {
        $numbers = [20, 12, 24, 15, 30];

        $lr = new LargestRemainder($numbers);

        $this->assertEquals(100, array_sum($lr->round()));
    }

    /**
     * @test
     */
    public function shouldWorkWithPrecision()
    {
        $numbers = [
            18.562874251497007,
            20.958083832335326,
            18.562874251497007,
            19.161676646706585,
            22.75449101796407
        ];

        $lr = new LargestRemainder($numbers);
        $lr->setPrecision(2);

        $actual = $lr->round();

        $this->assertEquals([
            18.55,
            20.94,
            18.55,
            19.15,
            22.74
        ], $actual);
    }

    /**
     * @test
     */
    public function shouldRoundWithPrecision2IfUnder100()
    {
        $numbers = [0.18, 0.12, 0.24, 0.15, 0.30];

        $lr = new LargestRemainder($numbers);
        $lr->setPrecision(2);

        $this->assertEquals(1, array_sum($lr->round()));
    }

    /**
     * @test
     */
    public function shouldKeepWhenIs100Already()
    {
        $numbers = [0.19, 0.12, 0.24, 0.15, 0.30];

        $lr = new LargestRemainder($numbers);
        $lr->setPrecision(2);

        $this->assertEquals(1, array_sum($lr->round()));
    }

    /**
     * @test
     */
    public function shouldRoundWithPrecision2IfOver100()
    {
        $numbers = [0.20, 0.12, 0.24, 0.15, 0.30];

        $lr = new LargestRemainder($numbers);
        $lr->setPrecision(2);

        $this->assertEquals(1, array_sum($lr->round()));
    }

    /**
     * @test
     */
    public function shouldRoundEvenIfDifferenceIsVeryLowAndInternalSortingChangesKeys()
    {
        $numbers = [0.5, 0.22727272727273, 0.18181818181818, 0.090909090909091];

        $lr = new LargestRemainder($numbers);
        $lr->setPrecision(2);

        $this->assertEquals(1, array_sum($lr->round()));
    }

    /**
     * @test
     */
    public function shouldRoundCallback()
    {
        $objects = [['a' => 0.20], ['a' => 0.12], ['a' => 0.24], ['a' => 0.15], ['a' => 0.30]];

        $lr = new LargestRemainder($objects);
        $lr->setPrecision(2);

        $this->assertEquals(
            1,
            array_reduce($lr->uround(
                function ($item) {
                    return $item['a'];
                },
                function (&$item, $value) {
                    $item['a'] = $value;
                }
            ), function ($carry, $item) {
                return $carry + $item['a'];
            }, 0)
        );
    }

    /**
     * @test
     */
    public function doNotTouchOriginalValues()
    {
        $numbers = [0.5, 0.22727272727273, 0.18181818181818, 0.090909090909091];

        $lr = new LargestRemainder($numbers);

        $lr->round();

        $this->assertSame([0.5, 0.22727272727273, 0.18181818181818, 0.090909090909091], $numbers);
    }

    /**
     * @test
     */
    public function shouldKeepOriginalSorting()
    {
        $numbers = [
            18.562874251497007,
            20.958083832335326,
            18.562874251497007,
            19.161676646706585,
            22.75449101796407
        ];

        $lr = new LargestRemainder($numbers);

        $actual = $lr->round();

        $this->assertEquals([
            19.0,
            21.0,
            18.0,
            19.0,
            23.0
        ], $actual);
    }
}
