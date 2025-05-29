<?php

namespace Altwaireb\Countries\Exceptions;

use Exception;

class FileNotFoundException extends Exception
{
    public static function fileNotFound(string $fileName): FileNotFoundException
    {
        return new self(message: "File Not Found {$fileName}.json}");
    }
}
