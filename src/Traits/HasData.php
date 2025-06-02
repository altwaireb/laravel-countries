<?php

namespace Altwaireb\Countries\Traits;

use Altwaireb\Countries\Exceptions\FileNotFoundException;

trait HasData
{
    /**
     * @throws FileNotFoundException
     */
    public function getCountries()
    {
        return $this->getJsonFileAsArray('countries');
    }

    /**
     * @throws FileNotFoundException
     */
    public function getStates(): array
    {
        return $this->getJsonFileAsArray('states');
    }

    /**
     * @throws FileNotFoundException
     */
    public function getCities(): array
    {
        return $this->getJsonFileAsArray('cities');
    }

    /**
     * @throws FileNotFoundException
     */
    private function getCountriesIdsBy(string $column, array $values): array
    {
        $countries = collect($this->getCountries());

        return $countries->whereIn($column, $values)->pluck('id')->toArray();
    }

    /**
     * @throws FileNotFoundException
     */
    protected function getIsoCodeCountryByColumn(int $countryId, string $column): string
    {
        $countries = collect($this->getCountries());

        return $countries->where($column, $countryId)->first($column);
    }

    /**
     * @throws FileNotFoundException
     */
    protected function getColumnCountriesByIds(array $countriesIds, string $column): array
    {
        $countries = collect($this->getCountries());

        return $countries->whereIn('id', $countriesIds)->pluck($column)->toArray();
    }

    /**
     * @throws FileNotFoundException
     */
    private function getJsonFileAsArray(string $fileName)
    {
        $data = file_get_contents(__DIR__."/../../database/data/$fileName.json");

        if (empty($data)) {
            throw new FileNotFoundException($fileName);
        }

        return json_decode($data, true);

    }
}
