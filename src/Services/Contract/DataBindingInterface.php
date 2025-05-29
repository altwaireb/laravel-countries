<?php

namespace Altwaireb\Countries\Services\Contract;

interface DataBindingInterface
{
    public function insert(
        string $table,
        array $activeColumnIds,
        array $exceptColumnIds,
        bool $onlyActivation,
        string $columnName,
        bool $defaultActivation,
        int $chunkSize,
        bool $withEncode,
    ): void;
}
