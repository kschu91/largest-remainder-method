<?php

namespace Aeq\LargestRemainder\Math;

use Aeq\LargestRemainder\Exception\AlreadyNormalizedException;
use Aeq\LargestRemainder\Exception\NotANumberException;
use Aeq\LargestRemainder\Exception\NotYetNormalizedException;
use PHPUnit\Framework\TestCase;

class LargestRemainderTest extends TestCase
{
    /**
     * @test
     * @throws AlreadyNormalizedException
     * @throws NotANumberException
     * @throws NotYetNormalizedException
     */
    public function shouldRoundIfUnder100(): void
    {
        $numbers = [18, 12, 24, 15, 30];

        $lr = new LargestRemainder($numbers);

        $this->assertEquals(100, array_sum($lr->round()));
    }

    /**
     * @test
     * @throws AlreadyNormalizedException
     * @throws NotANumberException
     * @throws NotYetNormalizedException
     */
    public function shouldRoundIfOver100(): void
    {
        $numbers = [20, 12, 24, 15, 30];

        $lr = new LargestRemainder($numbers);

        $this->assertEquals(100, array_sum($lr->round()));
    }

    /**
     * @test
     * @throws AlreadyNormalizedException
     * @throws NotANumberException
     * @throws NotYetNormalizedException
     */
    public function shouldWorkWithPrecision(): void
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
     * @throws AlreadyNormalizedException
     * @throws NotANumberException
     * @throws NotYetNormalizedException
     */
    public function shouldRoundWithPrecision2IfUnder100(): void
    {
        $numbers = [0.18, 0.12, 0.24, 0.15, 0.30];

        $lr = new LargestRemainder($numbers);
        $lr->setPrecision(2);

        $this->assertEquals(1, array_sum($lr->round()));
    }

    /**
     * @test
     * @throws AlreadyNormalizedException
     * @throws NotANumberException
     * @throws NotYetNormalizedException
     */
    public function shouldKeepWhenIs100Already(): void
    {
        $numbers = [0.19, 0.12, 0.24, 0.15, 0.30];

        $lr = new LargestRemainder($numbers);
        $lr->setPrecision(2);

        $this->assertEquals(1, array_sum($lr->round()));
    }

    /**
     * @test
     * @throws AlreadyNormalizedException
     * @throws NotANumberException
     * @throws NotYetNormalizedException
     */
    public function shouldRoundWithPrecision2IfOver100(): void
    {
        $numbers = [0.20, 0.12, 0.24, 0.15, 0.30];

        $lr = new LargestRemainder($numbers);
        $lr->setPrecision(2);

        $this->assertEquals(1, array_sum($lr->round()));
    }

    /**
     * @test
     * @throws AlreadyNormalizedException
     * @throws NotANumberException
     * @throws NotYetNormalizedException
     */
    public function shouldRoundEvenIfDifferenceIsVeryLowAndInternalSortingChangesKeys(): void
    {
        $numbers = [0.5, 0.22727272727273, 0.18181818181818, 0.090909090909091];

        $lr = new LargestRemainder($numbers);
        $lr->setPrecision(2);

        $this->assertEquals(1, array_sum($lr->round()));
    }

    /**
     * @test
     * @throws AlreadyNormalizedException
     * @throws NotANumberException
     * @throws NotYetNormalizedException
     */
    public function shouldRoundCallback(): void
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
     * @throws AlreadyNormalizedException
     * @throws NotANumberException
     * @throws NotYetNormalizedException
     */
    public function doNotTouchOriginalValues(): void
    {
        $numbers = [0.5, 0.22727272727273, 0.18181818181818, 0.090909090909091];

        $lr = new LargestRemainder($numbers);

        $lr->round();

        $this->assertSame([0.5, 0.22727272727273, 0.18181818181818, 0.090909090909091], $numbers);
    }

    /**
     * @test
     * @throws AlreadyNormalizedException
     * @throws NotANumberException
     * @throws NotYetNormalizedException
     */
    public function shouldKeepOriginalSorting(): void
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

    /**
     * @test
     * @throws AlreadyNormalizedException
     * @throws NotANumberException
     * @throws NotYetNormalizedException
     */
    public function shouldThrowNotANumberException(): void
    {
        $this->expectException(NotANumberException::class);
        $numbers = [
            18.562874251497007,
            20.958083832335326,
            'this is not a number',
            19.161676646706585,
            22.75449101796407
        ];

        $lr = new LargestRemainder($numbers);

        $lr->round();
    }
}
