<?php

namespace Altwaireb\Countries\Exceptions;

use Exception;

class ContradictionIsoCodeException extends Exception
{
    public static function isContradiction(string $code, string $status): ContradictionIsoCodeException
    {
        return new self(message: "This is a contradiction! You are trying to activate and except the country ISO $code at the same time in $status, please check ISO codes in config file 'config/countries.php");
    }
}
