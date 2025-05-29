<?php

namespace Altwaireb\Countries\Commands;

use Altwaireb\Countries\Countries;
use Illuminate\Console\Command;

class SeederCountriesCommand extends Command
{
    public $signature = 'countries:seeder
        {--R|refresh : Reset and restart migrations for countries/states/cities in the table }
    ';

    public $description = 'Seeder All Countries/States/Cities Data';

    public function __construct(
        protected Countries $serves
    ) {
        parent::__construct();
    }

    public function handle(): int
    {
        if (! $this->serves->isSeedersPublished()) {
            $this->components->error('Please RUN `php artisan vendor:publish --tag=countries-seeders` to publish seeder class');

            return self::INVALID;
        }

        if (! $this->serves->hasMigrationFileName(migrationFileName: 'create_countries_table.php')) {
            $this->components->error('Please RUN `php artisan vendor:publish --tag=countries-migrations` to publish migrations tables');

            return self::INVALID;
        }

        if ($this->option('refresh')) {
            if ($this->confirm('Are you sure you want to delete all data in the Countries/States/Cities tables?')) {
                $this->callSilently('migrate:refresh', [
                    '--path' => 'database/migrations/'.$this->serves->getMigrationFileName(migrationFileName: 'create_countries_table.php'),
                ]);
            } else {
                $this->components->warn('counsel command.');

                return self::INVALID;
            }
        } else {
            if (! $this->serves->isAllTablesEmpty()) {
                if (! $this->serves->isCountriesTableEmpty()) {
                    $this->components->error("You can't Seeding in countries table because table has data.");

                    return self::INVALID;
                }

                if (! $this->serves->isStatesTableEmpty()) {
                    $this->components->error("You can't Seeding in states table because table has data.");

                    return self::INVALID;
                }

                if (! $this->serves->isCitiesTableEmpty()) {
                    $this->components->error("You can't Seeding in cities table because table has data.");

                    return self::INVALID;
                }

                $this->components->warn('You can run `php artisan countries:seeder --refresh` this command delete tables countries/states/cities and re-seeding data.');
            }
        }

        $this->call('db:seed', [
            '--class' => 'Database\Seeders\CountriesTableSeeder',
        ]);

        return self::SUCCESS;
    }
}
