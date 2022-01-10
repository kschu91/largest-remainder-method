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
    public function shouldWorkWithoutPrecision(): void
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

        $this->assertSame(array_sum($actual), 100.0);
        $this->assertSame([
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

        $this->assertSame(array_sum($actual), 100.0);
        $this->assertSame([
            18.56,
            20.96,
            18.56,
            19.16,
            22.76
        ], $actual);
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

        $actual = $lr->round();

        $this->assertSame(1.0, array_sum($actual));
        $this->assertSame([
            0.5,
            0.23,
            0.18,
            0.09
        ], $actual);
    }

    /**
     * @test
     * @throws AlreadyNormalizedException
     * @throws NotANumberException
     * @throws NotYetNormalizedException
     */
    public function shouldRoundWithEqualValues(): void
    {
        $numbers = [0.015, 0.015];

        $lr = new LargestRemainder($numbers);
        $lr->setPrecision(2);

        $actual = $lr->round();

        $this->assertSame(0.03, array_sum($actual));
        $this->assertSame([0.02, 0.01], $lr->round());
    }

    /**
     * @test
     * @throws AlreadyNormalizedException
     * @throws NotANumberException
     * @throws NotYetNormalizedException
     */
    public function shouldRoundCallback(): void
    {
        $objects = [
            ['a' => 18.562874251497007],
            ['a' => 20.958083832335326],
            ['a' => 18.562874251497007],
            ['a' => 19.161676646706585],
            ['a' => 22.75449101796407]
        ];

        $lr = new LargestRemainder($objects);
        $lr->setPrecision(2);

        $actual = $lr->uround(
            function ($item) {
                return $item['a'];
            },
            function (&$item, $value) {
                $item['a'] = $value;
            }
        );

        $this->assertSame(100.0, array_reduce($actual, function ($carry, $item) {
            return $carry + $item['a'];
        }, 0));
        $this->assertSame([
            ['a' => 18.56],
            ['a' => 20.96],
            ['a' => 18.56],
            ['a' => 19.16],
            ['a' => 22.76]
        ], $actual);
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
