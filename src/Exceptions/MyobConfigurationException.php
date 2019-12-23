<?php

namespace Creativecurtis\Laramyob\Exceptions;

use Exception;

class MyobConfigurationException extends Exception
{
    public static function myobConfigurationNotFoundException()
    {
        return new static('You have not yet configured MYOB');
    }
}
