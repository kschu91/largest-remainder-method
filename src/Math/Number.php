<?php

namespace Aeq\LargestRemainder\Math;

use Aeq\LargestRemainder\Exception\AlreadyNormalizedException;
use Aeq\LargestRemainder\Exception\NotYetNormalizedException;

class Number
{
    /**
     * @var float
     */
    private $number = 0.0;

    /**
     * @var int
     */
    private $precision = 0;

    /**
     * @var float
     */
    private $isNormalized = false;

    /**
     * @param float $number
     * @param int $precision
     */
    public function __construct(float $number, int $precision = 0)
    {
        $this->number = $number;
        $this->precision = $precision;
    }

    /**
     * @return Number
     * @throws AlreadyNormalizedException
     */
    public function normalize(): self
    {
        if ($this->isNormalized) {
            throw new AlreadyNormalizedException(1538928749);
        }
        $this->number = $this->number * pow(10, $this->precision);
        $this->isNormalized = true;
        return $this;
    }

    /**
     * @return Number
     * @throws NotYetNormalizedException
     */
    public function denormalize(): self
    {
        if (false === $this->isNormalized) {
            throw new NotYetNormalizedException(1538928762);
        }
        $this->number = $this->number / pow(10, $this->precision);
        $this->isNormalized = false;
        return $this;
    }

    /**
     * @param $val
     * @return Number
     */
    public function add($val): self
    {
        $this->number += $val;
        return $this;
    }

    /**
     * @param $val
     * @return Number
     */
    public function sub($val): self
    {
        $this->number -= $val;
        return $this;
    }

    /**
     * @return Number
     */
    public function floor(): self
    {
        $this->number = floor($this->number);
        return $this;
    }

    /**
     * @return float
     */
    public function value(): float
    {
        return $this->number;
    }
}
