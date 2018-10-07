<?php

namespace Aeq\LargestRemainder\Exception;

use Throwable;

class NotANumberException extends \Exception
{
    public function __construct(string $val, int $code = 0, Throwable $previous = null)
    {
        parent::__construct(
            sprintf('%s is not a number, it´s a %s. Please provide a valid numeric value.', var_export($val, true), gettype($val)),
            $code,
            $previous
        );
    }
}
