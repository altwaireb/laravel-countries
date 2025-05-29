# Laravel Countries
This package allows you to add all Countries, States, and Cities data with DB Migration & Seeder for Laravel.

[![Latest Version on Packagist](https://img.shields.io/packagist/v/altwaireb/laravel-countries.svg?style=flat-square)](https://packagist.org/packages/altwaireb/laravel-countries)
[![GitHub Tests Action Status](https://img.shields.io/github/actions/workflow/status/altwaireb/laravel-countries/run-tests.yml?branch=main&label=tests&style=flat-square)](https://github.com/altwaireb/laravel-countries/actions?query=workflow%3Arun-tests+branch%3Amain)
[![GitHub Code Style Action Status](https://img.shields.io/github/actions/workflow/status/altwaireb/laravel-countries/fix-php-code-style-issues.yml?branch=main&label=code%20style&style=flat-square)](https://github.com/altwaireb/laravel-countries/actions?query=workflow%3A"Fix+PHP+code+style+issues"+branch%3Amain)
[![Total Downloads](https://img.shields.io/packagist/dt/altwaireb/laravel-countries.svg?style=flat-square)](https://packagist.org/packages/altwaireb/laravel-countries)

## Numbers
| Model   | Number of items |
|---------|-----------------|
| Country | 250             |
| State   | 4961            |
| City    | 148059          |


## Attributes

Common attributes:

- `name`: Common name of Country(english).
- `iso2`: ISO-3166-2 code.
- `iso2`: ISO-3166-3 code.
- `numeric_code`: Country Numeric code.
- `phonecode`: Country Phone code.
- `capital`: Capital of this country.
- `currency`: ISO-4177 Currency Code, e.g. USD, CNY.
- `currency_name`: Currency Name.
- `currency_symbol`: Currency Symbol e.g. $, ¥.
- `tld`: Country code top-level domain e.g. .uk.
- `native`: Local name of the country.
- `region`: region of the country.
- `subregion`: Sub-region of the country.
- `timezones`: timezones the country.
    - `zoneName`: Zone Name e.g. America/New_York.
    - `gmtOffset`: GMT offset e.g. -18000.
    - `gmtOffsetName`: GMT offset Name e.g. UTC-05:00.
    - `abbreviation`: abbreviation e.g. EST.
    - `tzName`: time zone Name e.g. Eastern Standard Time (North America).
- `translations`: Country name translations e.g.
    - "ar": "الولايات المتحدة الأمريكية"
    - "kr": "미국"
    - "fr": "États-Unis"
- `latitude`: latitude the country.
- `longitude`: latitude the country.
- `emoji`: Emoji flag of country e.g. 🇺🇸.
- `emojiU`: Emoji Unicode flag of country e.g U+1F1FA U+1F1F8.
- `flag`: Country has flag (boolean).
- `is_active`: Country has active (boolean).


## Installation

You can install the package via composer:

```bash
composer require altwaireb/laravel-countries
```

## Usage
Now run the following command to install .
```bash
php artisan countries:install
```

Add seeder File in `database\seeders\DatabaseSeeder.php` add this line to use `php artisan db:seed` command.
```php
public function run(): void
    {
    
        $this->call(CountriesTableSeeder::class);
        ...
    }
```

Or you can Seed Data of Countries States Cities, by run this command.
```bash
php artisan countries:seeder
```

And you can refresh to re-seeding Data of Countries States Cities, by run this command.
```bash
php artisan world:countries --refresh
```

You can specify the activation of countries through the country code ISO2 or ISO3,
before processing the seed data in the config file. `config/countries.php`
```php
return [
    'insert_activations_only' => false,
    'countries' => [
        'activation' => [
            'default' => true,
            'only' => [
                'iso2' => ['SA','GB','DE'],
                'iso3' => ['USA','BRA','EGY'],
            ],
            'except' => [
                'iso2' => ['GA'],
                'iso3' => ['HTI'],
            ],
        ],
        'chunk_length' => 50,
    ],

    'states' => [
        'activation' => [
            'default' => true,
        ],
        'chunk_length' => 200,
    ],

    'cities' => [
        'activation' => [
            'default' => true,
        ],
        'chunk_length' => 200,
    ],
];
```
If you need to insert the countries is activation , this insert only two Countries `( Albania , Argentina )` with States and Cities.
```php
return [
    'insert_activations_only' => true,
    'countries' => [
        'activation' => [
            'default' => true,
            'only' => [
                'iso2' => ['AL','AR'],
                'iso3' => [],
            ],
            'except' => [
                'iso2' => [],
                'iso3' => [],
            ],
        ],
        'chunk_length' => 50,
    ],

    ...
];
```


This means that only these two countries and the states and cities affiliated with them will be activated.
+ Note: If activation only `iso2` an `iso3` are empty, the column is_active take the `default` value in config file.
+ Note: If Country is active, all States and Cities are active.
+ Note: If activation except `iso2` or `iso3` the column is_active take FALSE value.
+ Note: If Country is not active, all States and Cities are not active.


## Usage

you can get country by iso2 and iso3 or both.

if you want to get country by iso2 you can yes static function getByIso2
```php
use App\Models\Country;

$sa = Country::getByIso2('SA');
$sa->name; // Saudi Arabia
$sa->iso2; // SA
$sa->iso3; // SAU
$sa->currency_symbol; // ﷼
$sa->native; // المملكة العربية السعودية
```

if you want to get country by iso3 you can use.
```php
use App\Models\Country;

$bra = Country::getByIso3('BRA');
$bra->name; // Brazil
$bra->iso2; // BR
$bra->iso3; // BRA
$bra->currency_symbol; // R$
$bra->native; // Brasil
```
also if you want to get country by code iso2 ro iso3 you can use.
```php
use App\Models\Country;

$bra = Country::getByCode('PT');
$bra->name; // Portugal
$bra->iso2; // PT
$bra->iso3; // PRT
$bra->currency_symbol; // €
$bra->native; // Portugal
```

### Scope
you can use Scope to filter data is Active by use.

```php
use App\Models\Country;

$countries = Country::active()->pluck('name','id');
```

## Credits

- [Abdulmajeed Altwaireb](https://github.com/altwaireb)
- [Alexandre Plennevaux](https://github.com/pixeline) (*Edit readme*)
- [All Contributors](../../contributors)