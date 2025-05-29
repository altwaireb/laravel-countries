<?php

namespace Altwaireb\Countries\Traits;

use Illuminate\Filesystem\Filesystem;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;

trait HasTables
{
    public function hasCountriesTable(): bool
    {
        return $this->hasTable(tableName: 'countries');
    }

    public function hasStatesTable(): bool
    {
        return $this->hasTable(tableName: 'states');
    }

    public function hasCitiesTable(): bool
    {
        return $this->hasTable(tableName: 'cities');
    }

    public function isAllTablesEmpty(): bool
    {
        return $this->isCountriesTableEmpty() === true &&
            $this->isStatesTableEmpty() === true &&
            $this->isCitiesTableEmpty() === true;
    }

    public function isCountriesTableEmpty(): bool
    {
        return $this->isTableEmpty(tableName: 'countries');
    }

    public function isStatesTableEmpty(): bool
    {
        return $this->isTableEmpty(tableName: 'states');
    }

    public function isCitiesTableEmpty(): bool
    {
        return $this->isTableEmpty(tableName: 'cities');
    }

    private function hasTable(string $tableName): bool
    {
        return Schema::hasTable($tableName);
    }

    private function isTableEmpty($tableName): bool
    {
        if (! $this->hasTable($tableName) && (DB::table($tableName)->count() > 0)) {
            return false;
        }

        return true;
    }

    public static function isSeedersPublished(): bool
    {
        return ! empty(glob(database_path('seeders/CountriesTableSeeder.php')));
    }

    public function getMigrationFileName(string $migrationFileName): ?string
    {

        $filesystem = app()->make(Filesystem::class);
        $databasePath = app()->databasePath().DIRECTORY_SEPARATOR.'migrations'.DIRECTORY_SEPARATOR;

        $fullFileName = Collection::make([$databasePath])
            ->flatMap(fn ($path) => $filesystem->glob($path.'*_'.$migrationFileName))
            ->first();

        if (is_null($fullFileName)) {
            return null;
        }

        return Str::afterLast($fullFileName, $databasePath);

    }

    public function hasMigrationFileName(string $migrationFileName): bool
    {
        return ! is_null($this->getMigrationFileName($migrationFileName));
    }
}
