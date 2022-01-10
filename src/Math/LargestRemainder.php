<?php

namespace Aeq\LargestRemainder\Math;

use Aeq\LargestRemainder\Exception\AlreadyNormalizedException;
use Aeq\LargestRemainder\Exception\NotANumberException;
use Aeq\LargestRemainder\Exception\NotYetNormalizedException;
use Aeq\LargestRemainder\Math\Number as LargestRemainderNumber;

class LargestRemainder
{
    /**
     * @var array
     */
    private $numbers = [];

    /**
     * @var int
     */
    private $precision = 0;

    /**
     * @param array $numbers
     */
    public function __construct(array $numbers)
    {
        $this->numbers = $numbers;
    }

    /**
     * @param int $precision
     */
    public function setPrecision(int $precision): void
    {
        $this->precision = $precision;
    }

    /**
     * @return array
     * @throws AlreadyNormalizedException
     * @throws NotANumberException
     * @throws NotYetNormalizedException
     */
    public function round(): array
    {
        return $this->uround(
            function ($item) {
                return $item;
            },
            function (&$item, $value) {
                $item = $value;
            }
        );
    }

    /**
     * @param callable $get
     * @param callable $set
     * @return array
     * @throws NotANumberException
     * @throws AlreadyNormalizedException
     * @throws NotYetNormalizedException
     */
    public function uround(callable $get, callable $set): array
    {
        $originalOrder = array_keys($this->numbers);

        $sum = 0;
        $floorSum = 0;

        foreach ($this->numbers as $raw) {
            $number = $this->getNumber($get, $raw);
            $sum += $number->value();
            $floorSum += $number->floor()->value();
        }

        $diff = round($sum) - round($floorSum);

        uasort($this->numbers, function ($a, $b) use ($get) {
            $aNumber = $this->getNumber($get, $a);
            $bNumber = $this->getNumber($get, $b);
            $aDiff = $aNumber->value() - $aNumber->floor()->value();
            $bDiff = $bNumber->value() - $bNumber->floor()->value();
            if($aDiff === $bDiff) {
                return 0;
            }
            if ($aDiff > $bDiff) {
                return -1;
            }
            return 1;
        });

        $index = 0;
        foreach ($this->numbers as &$item) {
            $number = $this->getNumber($get, $item);
            if ($index < $diff) {
                $this->setNumber($set, $item, $number->add(1)->floor());
                $index++;
                continue;
            }
            if ($diff < 0 && $index < $diff * (-1)) {
                $this->setNumber($set, $item, $number->sub(1)->floor());
                $index++;
                continue;
            }
            $this->setNumber($set, $item, $number->floor());
            $index++;
        }

        return array_replace(array_flip($originalOrder), $this->numbers);
    }

    /**
     * @param callable $get
     * @param $val
     * @return LargestRemainderNumber
     * @throws NotANumberException
     * @throws AlreadyNormalizedException
     */
    private function getNumber(callable $get, $val): LargestRemainderNumber
    {
        $resolved = call_user_func_array($get, [$val]);
        if (false === is_numeric($resolved)) {
            throw new NotANumberException($val, 1538927918);
        }
        return (new Number($resolved, $this->precision))->normalize();
    }

    /**
     * @param callable $set
     * @param $item
     * @param LargestRemainderNumber $number
     * @throws NotYetNormalizedException
     */
    private function setNumber(callable $set, &$item, LargestRemainderNumber $number): void
    {
        call_user_func_array($set, [&$item, $number->denormalize()->value()]);
    }
}
