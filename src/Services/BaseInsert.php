<?php

namespace Altwaireb\Countries\Services;

use Altwaireb\Countries\Exceptions\FileNotFoundException;
use Altwaireb\Countries\Exceptions\IsoCodesIsEmptyException;
use Illuminate\Support\Facades\DB;

class BaseInsert
{
    /**
     * @throws FileNotFoundException
     * @throws IsoCodesIsEmptyException
     */
    protected function withChunk(
        string $table,
        array $activeColumnIds,
        array $exceptColumnIds,
        bool $onlyActivation,
        string $columnName,
        bool $defaultActivation,
        int $chunkSize,
        bool $withEncode,
    ): void {
        self::setConfigurationOption();
        $items = $this->getContentsJsonFile(fileName: $table);

        if ($onlyActivation === true) {

            if (empty($activeColumnIds)) {
                throw IsoCodesIsEmptyException::isEmpty();
            } else {
                if (count($activeColumnIds) === 1) {
                    $data = $this->whereFromFile(
                        items: $items,
                        columnName: $columnName,
                        value: $activeColumnIds[0],
                        isActive: $defaultActivation,
                        withEncode: $withEncode
                    );

                } else {

                    $data = $this->whereInFromFile(
                        items: $items,
                        columnName: $columnName,
                        value: $activeColumnIds,
                        isActive: $defaultActivation,
                        withEncode: $withEncode
                    );
                }

                $this->create($table, $data, $chunkSize);
            }
        } elseif (! empty($activeColumnIds) && ! empty($exceptColumnIds)) {
            $data = collect($items)->map(function ($item) use ($columnName, $activeColumnIds, $exceptColumnIds, $withEncode) {
                if (in_array($item[$columnName], $activeColumnIds)) {
                    return $this->mapItem($item, true, $withEncode);
                } elseif (in_array($item[$columnName], $exceptColumnIds)) {
                    return $this->mapItem($item, false, $withEncode);
                } else {
                    return $this->mapItem($item, false, $withEncode);
                }
            });
            $this->create($table, $data, $chunkSize);

        }
        // @phpstan-ignore-next-line
        elseif (! empty($activeColumnIds) && empty($exceptColumnIds)) {
            $data = collect($items)->map(function ($item) use ($columnName, $activeColumnIds, $withEncode) {
                if (in_array($item[$columnName], $activeColumnIds)) {
                    return $this->mapItem($item, true, $withEncode);
                } else {
                    return $this->mapItem($item, false, $withEncode);
                }
            });
            $this->create($table, $data, $chunkSize);

        } elseif (empty($activeColumnIds) && ! empty($exceptColumnIds)) {
            $data = collect($items)->map(function ($item) use ($columnName, $exceptColumnIds, $withEncode) {
                if (in_array($item[$columnName], $exceptColumnIds)) {
                    return $this->mapItem($item, false, $withEncode);
                } else {
                    return $this->mapItem($item, true, $withEncode);
                }
            });
            $this->create($table, $data, $chunkSize);
        } else {
            $data = collect($items)
                ->map(function ($item) use ($defaultActivation, $withEncode) {
                    return $this->mapItem($item, $defaultActivation, $withEncode);
                });
            $this->create($table, $data, $chunkSize);
        }

    }

    private function create(
        string $table,
        $data,
        int $chunkSize,
    ): void {
        $countData = count($data);
        $tableName = ucfirst($table);
        $chunks = $data->chunk($chunkSize)->toArray();

        \Laravel\Prompts\info('');
        $progress = \Laravel\Prompts\progress(label: "Seeding {$tableName}", steps: $countData);
        $progress->start();

        $chunkCount = 0;

        foreach ($chunks as $chunk) {
            DB::table($table)->insert($chunk);
            $chunkCount = count($chunk) + $chunkCount;
            $progress->advance($chunkCount);

        }

        $progress->finish();

        \Laravel\Prompts\info(" {$tableName} Inserted Successfully !");

    }

    public function whereFromFile(
        array $items,
        string $columnName,
        mixed $value,
        bool $isActive,
        bool $withEncode,
    ) {
        return collect($items)->where($columnName, $value)
            ->map(function ($item) use ($isActive, $withEncode) {
                return $this->mapItem($item, $isActive, $withEncode);
            });
    }

    public function whereInFromFile(
        array $items,
        string $columnName,
        mixed $value,
        bool $isActive,
        bool $withEncode,
    ) {
        return collect($items)->whereIn($columnName, $value)
            ->map(function ($item) use ($isActive, $withEncode) {
                return $this->mapItem($item, $isActive, $withEncode);
            });
    }

    public function whereNotFromFile(
        array $items,
        string $columnName,
        mixed $value,
        bool $isActive,
        bool $withEncode,
    ) {
        return collect($items)->where($columnName, '!==', $value)
            ->map(function ($item) use ($isActive, $withEncode) {
                return $this->mapItem($item, $isActive, $withEncode);
            });
    }

    public function whereNotInFromFile(
        array $items,
        string $columnName,
        mixed $value,
        bool $isActive,
        bool $withEncode,
    ) {
        return collect($items)->whereNotIn($columnName, $value)
            ->map(function ($item) use ($isActive, $withEncode) {
                return $this->mapItem($item, $isActive, $withEncode);
            });
    }

    protected function mapItem($item, bool $isActive, bool $withEncode): array
    {
        if ($withEncode === true) {
            $item['translations'] = json_encode($item['translations'], JSON_UNESCAPED_UNICODE);
            $item['timezones'] = json_encode($item['timezones'], JSON_UNESCAPED_UNICODE);
        }

        return array_merge(
            $item,
            ['is_active' => $isActive]
        );
    }

    /**
     * @throws FileNotFoundException
     */
    protected function getContentsJsonFile($fileName)
    {
        $items = file_get_contents(__DIR__."/../../database/data/{$fileName}.json");
        if (empty($items)) {
            throw new FileNotFoundException($fileName);
        }

        return json_decode($items, true);

    }

    protected static function setConfigurationOption(): void
    {
        ini_set('MAX_EXECUTION_TIME', -1);
        ini_set('memory_limit', -1);
    }
}
