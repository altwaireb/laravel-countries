<?php

namespace Altwaireb\Countries\Traits;

use Altwaireb\Countries\Exceptions\ContradictionIsoCodeException;
use Altwaireb\Countries\Exceptions\DuplicateIsoCodeException;
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
     * @throws DuplicateIsoCodeException
     * @throws ContradictionIsoCodeException
     */
    public function ensureEverythingIsDone(): void
    {
        $this->ensureIsInsertActivationsHasIsoCodes();
        $this->ensureIsoCodesIsValid();
        $this->ensureIsNoContradiction(
            activateIds: $this->getIdsCountriesActive(),
            exceptIds: $this->getIdsCountriesExcept()
        );
    }

    /**
     * @throws FileNotFoundException
     * @throws DuplicateIsoCodeException
     */
    protected function mergeIdsCountries(array $firstIds, string $firstColumn, array $secondIds, string $secondColumn, string $status): array
    {
        $this->ensureIsIdsNotDuplicated(
            firstIds: $firstIds,
            firstColumn: $firstColumn,
            secondIds: $secondIds,
            secondColumn: $secondColumn,
            status: $status
        );

        $ids = array_merge(
            $firstIds,
            $secondIds
        );

        if (empty($ids)) {
            return [];
        }

        return array_unique($ids);

    }

    /**
     * @throws FileNotFoundException
     * @throws DuplicateIsoCodeException
     */
    protected function ensureIsIdsNotDuplicated(array $firstIds, string $firstColumn, array $secondIds, string $secondColumn, string $status): void
    {
        $idsIsDuplicated = array_values(array_intersect($firstIds, $secondIds));

        if (! empty($idsIsDuplicated)) {
            if (! in_array($secondIds, $idsIsDuplicated)) {
                $codes = $this->getColumnCountriesByIds(
                    countriesIds: $idsIsDuplicated,
                    column: $secondColumn
                );
                $iosCodes = implode(',', $codes);
                if (count($codes) === 1) {
                    throw DuplicateIsoCodeException::isDuplicate(code: $iosCodes, status: $status);
                } else {
                    $iosCodes = implode(',', $codes);
                    throw DuplicateIsoCodeException::isDuplicates(codes: $iosCodes, status: $status);
                }
            } elseif (! in_array($firstIds, $idsIsDuplicated)) {
                $codes = $this->getColumnCountriesByIds(
                    countriesIds: $idsIsDuplicated,
                    column: $firstColumn
                );
                $iosCodes = implode(',', $codes);
                if (count($codes) === 1) {
                    throw DuplicateIsoCodeException::isDuplicate(code: $iosCodes, status: $status);
                } else {
                    $iosCodes = implode(',', $codes);
                    throw DuplicateIsoCodeException::isDuplicates(codes: $iosCodes, status: $status);
                }
            }
        }
    }

    /**
     * @throws FileNotFoundException
     * @throws ContradictionIsoCodeException
     */
    protected function ensureIsNoContradiction(array $activateIds, array $exceptIds): void
    {

        $idsIsActivateAndExcept = array_values(array_intersect($activateIds, $exceptIds));

        if (! empty($idsIsActivateAndExcept)) {
            if (! in_array($activateIds, $idsIsActivateAndExcept)) {
                if (! in_array($this->getIdsCountriesActiveByIso2(), $idsIsActivateAndExcept)) {
                    $codes = $this->getColumnCountriesByIds(
                        countriesIds: $idsIsActivateAndExcept,
                        column: 'iso2',
                    );
                    $iosCodes = implode(',', $codes);
                    throw ContradictionIsoCodeException::isContradiction(
                        code: $iosCodes,
                        status: 'active'
                    );

                } elseif (! in_array($this->getIdsCountriesActiveByIso3(), $idsIsActivateAndExcept)) {
                    $codes = $this->getColumnCountriesByIds(
                        countriesIds: $idsIsActivateAndExcept,
                        column: 'iso3',
                    );
                    $iosCodes = implode(',', $codes);
                    throw ContradictionIsoCodeException::isContradiction(
                        code: $iosCodes,
                        status: 'active'
                    );
                }
            } elseif (! in_array($exceptIds, $idsIsActivateAndExcept)) {
                if (! in_array($this->getIdsCountriesExceptByIso2(), $idsIsActivateAndExcept)) {
                    $codes = $this->getColumnCountriesByIds(
                        countriesIds: $idsIsActivateAndExcept,
                        column: 'iso2',
                    );
                    $iosCodes = implode(',', $codes);
                    throw ContradictionIsoCodeException::isContradiction(
                        code: $iosCodes,
                        status: 'except'
                    );

                } elseif (! in_array($this->getIdsCountriesExceptByIso3(), $idsIsActivateAndExcept)) {
                    $codes = $this->getColumnCountriesByIds(
                        countriesIds: $idsIsActivateAndExcept,
                        column: 'iso3',
                    );
                    $iosCodes = implode(',', $codes);
                    throw ContradictionIsoCodeException::isContradiction(
                        code: $iosCodes,
                        status: 'except'
                    );
                }
            }
        }
    }
}
