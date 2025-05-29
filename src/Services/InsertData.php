<?php

namespace Altwaireb\Countries\Services;

use Altwaireb\Countries\Exceptions\FileNotFoundException;
use Altwaireb\Countries\Exceptions\IsoCodesIsEmptyException;
use Altwaireb\Countries\Services\Contract\DataBindingInterface;

class InsertData extends BaseInsert implements DataBindingInterface
{
    /**
     * @throws FileNotFoundException
     * @throws IsoCodesIsEmptyException
     */
    public function insert(
        string $table,
        array $activeColumnIds,
        array $exceptColumnIds,
        bool $onlyActivation,
        string $columnName,
        bool $defaultActivation,
        int $chunkSize,
        bool $withEncode,
    ): void {
        $this->withChunk(
            table: $table,
            activeColumnIds: $activeColumnIds,
            exceptColumnIds: $exceptColumnIds,
            onlyActivation: $onlyActivation,
            columnName: $columnName,
            defaultActivation: $defaultActivation,
            chunkSize: $chunkSize,
            withEncode: $withEncode,
        );
    }
}
