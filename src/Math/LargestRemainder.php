<?php

namespace Aeq\LargestRemainder\Math;

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
    public function setPrecision(int $precision)
    {
        $this->precision = $precision;
    }

    /**
     * @return array
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
     */
    public function uround(callable $get, callable $set): array
    {
        $originalOrder = array_keys($this->numbers);

        $sum = array_sum(array_map(function ($item) use ($get) {
            return floor($this->normalize(call_user_func_array($get, [$item])));
        }, $this->numbers));

        $diff = 100 - $sum;

        uasort($this->numbers, function ($a, $b) use ($get) {
            $aNumber = $this->normalize(call_user_func_array($get, [$a]));
            $bNumber = $this->normalize(call_user_func_array($get, [$b]));
            return $aNumber - floor($aNumber) < $bNumber - floor($bNumber);
        });

        $index = 0;
        foreach ($this->numbers as &$item) {
            $number = call_user_func_array($get, [&$item]);
            $normalized = $this->normalize($number);
            if ($index < $diff) {
                call_user_func_array($set, [&$item, $this->denormalize(floor($normalized + 1))]);
                $index++;
                continue;
            }
            if ($diff < 0 && $index < $diff * (-1)) {
                call_user_func_array($set, [&$item, $this->denormalize(floor($normalized - 1))]);
                $index++;
                continue;
            }
            call_user_func_array($set, [&$item, $this->denormalize(floor($normalized))]);
            $index++;
            continue;
        }

        return array_replace(array_flip($originalOrder), $this->numbers);
    }

    /**
     * @param $val
     * @return float
     */
    private function normalize($val): float
    {
        return $val * pow(10, $this->precision);
    }

    /**
     * @param $val
     * @return float
     */
    private function denormalize($val): float
    {
        return $val / pow(10, $this->precision);
    }
}
