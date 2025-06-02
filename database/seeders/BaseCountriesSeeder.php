<?php

namespace Altwaireb\Countries\Database\Seeders;

use Altwaireb\Countries\Countries;
use Altwaireb\Countries\Exceptions\ContradictionIsoCodeException;
use Altwaireb\Countries\Exceptions\DuplicateIsoCodeException;
use Altwaireb\Countries\Exceptions\FileNotFoundException;
use Altwaireb\Countries\Exceptions\InvalidCodeException;
use Altwaireb\Countries\Exceptions\IsoCodesIsEmptyException;
use Illuminate\Database\Seeder;

class BaseCountriesSeeder extends Seeder
{
    public function __construct(
        protected Countries $serves
    ) {}

    /**
     * @throws FileNotFoundException
     * @throws DuplicateIsoCodeException
     * @throws IsoCodesIsEmptyException
     * @throws ContradictionIsoCodeException
     * @throws InvalidCodeException
     */
    public function run(): void
    {

        $this->serves->ensureEverythingIsDone();
        $this->serves->seedingData();
    }
}
