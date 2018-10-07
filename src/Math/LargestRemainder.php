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
    private $normalizer = 1;

    /**
     * @param array $numbers
     */
    public function __construct(array $numbers)
    {
        $this->numbers = $numbers;
    }

    /**
     * @param int $normalizer
     */
    public function setNormalizer(int $normalizer)
    {
        $this->normalizer = $normalizer;
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
        $sum = array_sum(array_map(function ($item) use ($get) {
            return floor(call_user_func_array($get, [$item]) * $this->normalizer);
        }, $this->numbers));

        $diff = 100 - $sum;

        uasort($this->numbers, function ($a, $b) use ($get) {
            $aNumber = call_user_func_array($get, [$a]) * $this->normalizer;
            $bNumber = call_user_func_array($get, [$b]) * $this->normalizer;
            return $aNumber - floor($aNumber) < $bNumber - floor($bNumber);
        });

        $index = 0;
        foreach ($this->numbers as &$item) {
            $number = call_user_func_array($get, [&$item]);
            $normalized = $number * $this->normalizer;
            if ($index < $diff) {
                call_user_func_array($set, [&$item, floor($normalized + 1) / $this->normalizer]);
                $index++;
                continue;
            }
            if ($diff < 0 && $index < $diff * (-1)) {
                call_user_func_array($set, [&$item, floor($normalized - 1) / $this->normalizer]);
                $index++;
                continue;
            }
            call_user_func_array($set, [&$item, floor($normalized) / $this->normalizer]);
            $index++;
            continue;
        }

        return $this->numbers;
    }
}
