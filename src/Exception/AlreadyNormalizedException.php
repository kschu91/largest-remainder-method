<?php

namespace Aeq\LargestRemainder\Exception;

use Throwable;

class AlreadyNormalizedException extends \Exception
{
    public function __construct(int $code = 0, Throwable $previous = null)
    {
        parent::__construct(
            'seems like you are trying to normalize an already normalized number',
            $code,
            $previous
        );
    }
}
