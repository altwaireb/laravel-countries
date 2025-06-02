<?php

namespace Altwaireb\Countries\Exceptions;

use Exception;

class DuplicateIsoCodeException extends Exception
{
    public static function isDuplicate(string $code, string $status): DuplicateIsoCodeException
    {
        return new self(message: "Duplicate ISO $code code refers to the same country in $status, please check ISO codes in config file 'config/countries.php");
    }

    public static function isDuplicates(string $codes, string $status): DuplicateIsoCodeException
    {
        return new self(message: "Duplicate ISO $codes codes refers to the same country in $status, please check ISO codes in config file 'config/countries.php");
    }
}
