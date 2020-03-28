<?php

/**
 * Created by PhpStorm.
 * User: zero
 * Date: 2020/3/17
 */

namespace Meibuyu\Micro\Exceptions;

use Throwable;

class ObjectNotExistException extends \Exception
{
    public function __construct($message, $code = 0, Throwable $previous = null)
    {
        parent::__construct($message . ' Not Exist!', $code, $previous);
    }
}