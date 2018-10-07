<?php

namespace Aeq\LargestRemainder\Exception;

use Throwable;

class NotYetNormalizedException extends \Exception
{
    public function __construct(int $code = 0, Throwable $previous = null)
    {
        parent::__construct(
            'seems like you are trying to denormalize a number that has not yet been normalized',
            $code,
            $previous
        );
    }
}
