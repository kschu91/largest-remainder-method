<?php

namespace Aeq\LargestRemainder\Math;

use Aeq\LargestRemainder\Exception\AlreadyNormalizedException;
use Aeq\LargestRemainder\Exception\NotYetNormalizedException;
use PHPUnit\Framework\TestCase;

class NumberTest extends TestCase
{
    /**
     * @test
     */
    public function value()
    {
        $number = new Number(5.68412);

        $this->assertSame(5.68412, $number->value());
    }

    /**
     * @test
     */
    public function floor()
    {
        $number = new Number(5.68412);

        $this->assertSame(5.0, $number->floor()->value());
    }

    /**
     * @test
     */
    public function add()
    {
        $number = new Number(5.68412);

        $this->assertSame(12.91412, $number->add(7.23)->value());
    }

    /**
     * @test
     */
    public function sub()
    {
        $number = new Number(5.68412);

        $this->assertSame(3.45412, $number->sub(2.23)->value());
    }

    /**
     * @test
     * @throws AlreadyNormalizedException
     */
    public function normalize()
    {
        $number = new Number(0.68412, 2);

        $this->assertSame(68.412, $number->normalize()->value());
    }

    /**
     * @test
     * @throws AlreadyNormalizedException
     */
    public function alreadyNormalized()
    {
        $this->expectException(AlreadyNormalizedException::class);

        $number = new Number(0.68412, 2);

        $number->normalize()->normalize();
    }

    /**
     * @test
     * @throws AlreadyNormalizedException
     * @throws NotYetNormalizedException
     */
    public function denormalize()
    {
        $number = new Number(0.68412, 2);

        $this->assertSame(0.68412, $number->normalize()->denormalize()->value());
    }

    /**
     * @test
     * @throws NotYetNormalizedException
     */
    public function notYetNormalized()
    {
        $this->expectException(NotYetNormalizedException::class);

        $number = new Number(0.68412, 2);

        $number->denormalize();
    }
}
