<?php

namespace Altwaireb\Countries\Traits;

use Altwaireb\Countries\Exceptions\DuplicateIsoCodeException;
use Altwaireb\Countries\Exceptions\FileNotFoundException;

trait HasException
{
    public function hasCountriesIso2Except(): bool
    {
        return ! empty($this->getCountriesIso2Except());
    }

    public function hasCountriesIso3Except(): bool
    {
        return ! empty($this->getCountriesIso3Except());
    }

    public function hasCountriesIsoCodeExcept(): bool
    {
        return $this->hasCountriesIso2Except() || $this->hasCountriesIso3Except();
    }

    /**
     * @throws FileNotFoundException
     */
    public function getIdsCountriesExceptByIso2(): array
    {
        if (! $this->hasCountriesIso2Except()) {
            return [];
        }

        return $this->getCountriesIdsBy(
            column: 'iso2',
            values: $this->getCountriesIso2Except()
        );
    }

    /**
     * @throws FileNotFoundException
     */
    public function getIdsCountriesExceptByIso3(): array
    {
        if (! $this->hasCountriesIso3Except()) {
            return [];
        }

        return $this->getCountriesIdsBy(
            column: 'iso3',
            values: $this->getCountriesIso3Except()
        );
    }

    /**
     * @throws FileNotFoundException
     * @throws DuplicateIsoCodeException
     */
    public function getIdsCountriesExcept(): array
    {
        return $this->mergeIdsCountries(
            firstIds: $this->getIdsCountriesExceptByIso2(),
            firstColumn: 'iso2',
            secondIds: $this->getIdsCountriesExceptByIso3(),
            secondColumn: 'iso3',
            status: 'except'
        );
    }

    protected function getIso2ActivateOrExcept(): array
    {
        $iso2 = [];
        if ($this->hasCountriesIso2Activate()) {
            $iso2 = array_merge($iso2, $this->getCountriesIso2Activate());
        }
        if ($this->hasCountriesIso2Except()) {
            $iso2 = array_merge($iso2, $this->getCountriesIso2Except());
        }

        if (! empty($iso2)) {
            return array_unique($iso2);
        }

        return $iso2;
    }

    protected function getIso3ActivateOrExcept(): array
    {
        $iso3 = [];
        if ($this->hasCountriesIso3Activate()) {
            $iso3 = array_merge($iso3, $this->getCountriesIso3Activate());
        }
        if ($this->hasCountriesIso3Except()) {
            $iso3 = array_merge($iso3, $this->getCountriesIso3Except());
        }

        if (! empty($iso3)) {
            return array_unique($iso3);
        }

        return $iso3;
    }
}
