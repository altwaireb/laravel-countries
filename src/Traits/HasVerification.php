<?php

namespace Altwaireb\Countries\Traits;

use Altwaireb\Countries\Exceptions\FileNotFoundException;
use Altwaireb\Countries\Exceptions\InvalidCodeException;
use Altwaireb\Countries\Exceptions\IsoCodesIsEmptyException;

trait HasVerification
{
    /**
     * @throws FileNotFoundException
     * @throws InvalidCodeException
     */
    public function ensureIsoCodesIsValid(): void
    {
        $this->ensureIso2IsValid();
        $this->ensureIso3IsValid();
    }

    /**
     * @throws FileNotFoundException
     * @throws InvalidCodeException
     */
    protected function ensureIso2IsValid(): void
    {
        if (! empty($this->getIso2ActivateOrExcept())) {
            foreach ($this->getIso2ActivateOrExcept() as $value) {
                if (! $this->isIsoCodeValid(
                    column: 'iso2',
                    value: $value
                )) {
                    throw InvalidCodeException::iso2CodeNotFound($value);
                }
            }
        }
    }

    /**
     * @throws \Altwaireb\Countries\Exceptions\FileNotFoundException
     * @throws \Altwaireb\Countries\Exceptions\InvalidCodeException
     */
    protected function ensureIso3IsValid(): void
    {
        if (! empty($this->getIso3ActivateOrExcept())) {
            foreach ($this->getIso3ActivateOrExcept() as $value) {
                if (! $this->isIsoCodeValid(
                    column: 'iso3',
                    value: $value
                )) {
                    throw InvalidCodeException::iso3CodeNotFound($value);
                }
            }
        }
    }

    /**
     * @throws FileNotFoundException
     */
    private function isIsoCodeValid(string $column, string $value): bool
    {
        $validate = collect(self::getCountries())
            ->pluck($column);
        $validate = $validate->all();

        return in_array($value, $validate);
    }

    /**
     * @throws FileNotFoundException
     * @throws InvalidCodeException
     * @throws IsoCodesIsEmptyException
     */
    public function ensureEverythingIsDone(): void
    {
        $this->ensureIsInsertActivationsHasIsoCodes();
        $this->ensureIsoCodesIsValid();
    }
}
