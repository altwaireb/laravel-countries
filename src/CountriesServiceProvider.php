<?php

namespace Altwaireb\Countries;

use Altwaireb\Countries\Commands\InstallCountriesCommand;
use Altwaireb\Countries\Commands\SeederCountriesCommand;
use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;

class CountriesServiceProvider extends PackageServiceProvider
{
    public function configurePackage(Package $package): void
    {
        $package
            ->name('laravel-countries')
            ->hasConfigFile()
            ->hasViews()
            ->hasMigration('create_countries_table')
            ->hasCommands([
                InstallCountriesCommand::class,
                SeederCountriesCommand::class,
            ]);
    }

    public function bootingPackage(): void
    {
        parent::bootingPackage();

        if ($this->app->runningInConsole()) {
            // publishes Models
            $this->publishes([
                __DIR__.'/../stubs/Models/Country.php.stub' => app_path('Models/Country.php'),
                __DIR__.'/../stubs/Models/State.php.stub' => app_path('Models/State.php'),
                __DIR__.'/../stubs/Models/City.php.stub' => app_path('Models/City.php'),
            ], 'countries-models');
            // publishes Seeders
            $this->publishes([
                __DIR__.'/../stubs/database/seeders/CountriesTableSeeder.php.stub' => database_path('seeders/CountriesTableSeeder.php'),
            ], 'countries-seeders');
        }
    }
}
