<?php

namespace Altwaireb\Countries\Traits;

use Altwaireb\Countries\Exceptions\FileNotFoundException;
use Altwaireb\Countries\Exceptions\IsoCodesIsEmptyException;
use Altwaireb\Countries\Services\InsertData;

trait HasInsertable
{
    /**
     * @throws FileNotFoundException
     * @throws IsoCodesIsEmptyException
     */
    public function seedingData(): void
    {
        $this->seedingCountries();
        $this->seedingStates();
        $this->seedingCities();

    }

    /**
     * @throws FileNotFoundException
     * @throws IsoCodesIsEmptyException
     */
    protected function seedingCountries(): void
    {
        $insertData = new InsertData;
        $insertData->insert(
            table: 'countries',
            activeColumnIds: $this->getIdsCountriesActive(),
            exceptColumnIds: $this->getIdsCountriesExcept(),
            onlyActivation: $this->IsInsertActivationsOnly(),
            columnName: 'id',
            defaultActivation: $this->isActivateCountries(),
            chunkSize: $this->getChunkLength(),
            withEncode: true
        );

    }

    /**
     * @throws FileNotFoundException
     * @throws IsoCodesIsEmptyException
     */
    protected function seedingStates(): void
    {
        $insertData = new InsertData;
        $insertData->insert(
            table: 'states',
            activeColumnIds: $this->getIdsCountriesActive(),
            exceptColumnIds: $this->getIdsCountriesExcept(),
            onlyActivation: $this->IsInsertActivationsOnly(),
            columnName: 'country_id',
            defaultActivation: $this->isActivateCountries(),
            chunkSize: $this->getChunkLength(),
            withEncode: false,
        );
    }

    /**
     * @throws FileNotFoundException
     * @throws IsoCodesIsEmptyException
     */
    protected function seedingCities(): void
    {
        $insertData = new InsertData;
        $insertData->insert(
            table: 'cities',
            activeColumnIds: $this->getIdsCountriesActive(),
            exceptColumnIds: $this->getIdsCountriesExcept(),
            onlyActivation: $this->IsInsertActivationsOnly(),
            columnName: 'country_id',
            defaultActivation: $this->isActivateCountries(),
            chunkSize: $this->getChunkLength(),
            withEncode: false,
        );

    }
}
