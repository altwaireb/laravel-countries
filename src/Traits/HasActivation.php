<?php

namespace Altwaireb\Countries\Traits;

use Altwaireb\Countries\Exceptions\FileNotFoundException;
use Altwaireb\Countries\Exceptions\IsoCodesIsEmptyException;

trait HasActivation
{
    public function hasCountriesIso2Activate(): bool
    {
        return ! empty($this->getCountriesIso2Activate());
    }

    public function hasCountriesIso3Activate(): bool
    {
        return ! empty($this->getCountriesIso3Activate());
    }

    public function hasCountriesIsoCodeActivate(): bool
    {
        return $this->hasCountriesIso2Activate() || $this->hasCountriesIso3Activate();
    }

    /**
     * @throws FileNotFoundException
     */
    public function getIdsCountriesActiveByIso2(): array
    {
        if (! $this->hasCountriesIso2Activate()) {
            return [];
        }

        return $this->getCountriesIdsBy(
            column: 'iso2',
            values: self::getCountriesIso2Activate()
        );
    }

    /**
     * @throws FileNotFoundException
     */
    public function getIdsCountriesActiveByIso3(): array
    {
        if (! $this->hasCountriesIso3Activate()) {
            return [];
        }

        return $this->getCountriesIdsBy(
            column: 'iso3',
            values: $this->getCountriesIso3Activate()
        );
    }

    /**
     * @throws FileNotFoundException
     */
    public function getIdsCountriesActive(): array
    {
        $idsIso2 = $this->getIdsCountriesActiveByIso2();
        $idsIso3 = $this->getIdsCountriesActiveByIso3();
        $ids = array_merge(
            $idsIso2,
            $idsIso3
        );

        if (empty($ids)) {
            return [];
        }

        return array_unique($ids);
    }

    /**
     * @throws IsoCodesIsEmptyException
     */
    public function ensureIsInsertActivationsHasIsoCodes(): void
    {
        if ($this->IsInsertActivationsOnly() === true) {
            if (! $this->hasCountriesIsoCodeActivate()) {
                throw IsoCodesIsEmptyException::isEmpty();
            }
        }
    }
}
