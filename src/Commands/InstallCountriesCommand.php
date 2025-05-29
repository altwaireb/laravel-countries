<?php

namespace Altwaireb\Countries\Commands;

use Illuminate\Console\Command;

class InstallCountriesCommand extends Command
{
    public $signature = 'countries:install
            {--F|force : Override if all files already exists }
            ';

    public $description = 'Install altwaireb/laravel-countries package';

    protected ?string $starRepo = 'altwaireb/laravel-countries';

    public function handle(): void
    {
        $force = ! empty($this->option('force'));

        $this->call('vendor:publish', [
            '--tag' => 'countries-config',
            '--force' => $force,
        ]);

        $this->call('vendor:publish', [
            '--tag' => 'countries-migrations',
            '--force' => $force,
        ]);

        $this->call('vendor:publish', [
            '--tag' => 'countries-models',
            '--force' => $force,
        ]);

        $this->call('vendor:publish', [
            '--tag' => 'countries-seeders',
            '--force' => $force,
        ]);

        if ($this->confirm('Would you like to run the migrations now?')) {
            $this->comment('Running migrations...');

            $this->call('migrate');
        }

        if ($this->confirm('Would you like to star our repo on GitHub?')) {
            $repoUrl = "https://github.com/{$this->starRepo}";

            if (PHP_OS_FAMILY == 'Darwin') {
                exec("open {$repoUrl}");
            }
            if (PHP_OS_FAMILY == 'Windows') {
                exec("start {$repoUrl}");
            }
            if (PHP_OS_FAMILY == 'Linux') {
                exec("xdg-open {$repoUrl}");
            }
        }

        $this->info('The Countries Package has been installed successfully.');

    }
}
