<?php

namespace Altwaireb\Countries\Traits;

trait HasConfiguration
{
    public function IsInsertActivationsOnly(): bool
    {
        return config('countries.insert_activations_only');
    }

    public function isActivateCountries(): bool
    {
        return config('countries.countries.activation.default');
    }

    public function isActivateStates(): bool
    {
        return config('countries.states.activation.default');
    }

    public function isActivateCities(): bool
    {
        return config('countries.cities.activation.default');
    }

    public static function getCountriesIso2Activate(): array
    {
        return config('countries.countries.activation.only.iso2');
    }

    public static function getCountriesIso3Activate(): array
    {
        return config('countries.countries.activation.only.iso3');
    }

    public static function getCountriesIso2Except(): array
    {
        return config('countries.countries.activation.except.iso2');
    }

    public static function getCountriesIso3Except(): array
    {
        return config('countries.countries.activation.except.iso3');
    }

    public static function getChunkLength(): int
    {
        return config('countries.chunk_length');
    }
}
