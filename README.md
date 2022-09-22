# Tile for Stockholm's public transport

[![Latest Version on Packagist](https://img.shields.io/packagist/v/robbens/laravel-dashboard-sl.svg?style=flat-square)](https://packagist.org/packages/robbens/laravel-dashboard-sl)
[![GitHub Tests Action Status](https://img.shields.io/github/workflow/status/robbens/laravel-dashboard-sl/run-tests?label=tests)](https://github.com/robbens/laravel-dashboard-sl/actions?query=workflow%3Arun-tests+branch%3Amaster)
[![Total Downloads](https://img.shields.io/packagist/dt/robbens/laravel-dashboard-sl.svg?style=flat-square)](https://packagist.org/packages/robbens/laravel-dashboard-sl)

A tile for [Laravel Dashboard](https://docs.spatie.be/laravel-dashboard) that shows real time public transport data from [Stockholms Lokaltrafik](https://sl.se).

![img](docs/sl-tile.png)

## Installation

You can install the package via composer:

```bash
composer require robbens/laravel-dashboard-sl
```

## Usage

In your dashboard view you use the `livewire:sl-tile` component.

```html
<x-dashboard>
    <livewire:sl-tile position="a1:a2" />
</x-dashboard>
```

Configure `config/dashboard.php`

```php
[
    // ...
    'tiles' => [
        'sl' => [
            /**
             * API key to SL Real-time information 4
             *
             * @link https://www.trafiklab.se/api/sl-realtidsinformation-4
             */
            'api_key' => env('TRAFIKLAB_KEY'),

            /**
             * ID for the place for which the departures are to be collected, eg 9192 for Slussen.
             * This ID can be found using the "SL Location Lookup Console".
             *
             * @link https://www.trafiklab.se/api/sl-platsuppslag/konsol
             */
            'site_id' => 1079,

            /**
             * Optional label to show on the top of the tile.
             */
            'site_label' => 'Odenplan',

            /**
             * Get departures within the desired time window. Where the
             * time window is the number of minutes from now. Max 60.
             */
            'time_window' => 60,

            /**
             * Transport modes limit.
             */
            'buses' => [
                'total_limit' => 5,
                // Filter out early departures by minute. This is usually how long it takes to walk to the station. 
                'early_departures_limit' => 5,
            ],
            'metros' => [
                'total_limit' => 5,
                'early_departures_limit' => null,
            ],
            'trains' => false,
        ], 
    ]   
]
```

Schedule the command in `app/Console/Kernel.php`.

```php
protected function schedule(Schedule $schedule)
{
    $schedule->command(Robbens\SlTile\FetchDataFromSLApiCommand::class)->everyMinute();
}
```

## Testing

``` bash
composer test
```

## Changelog

Please see [CHANGELOG](CHANGELOG.md) for more information on what has changed recently.

## Contributing

Please see [CONTRIBUTING](CONTRIBUTING.md) for details.

## Security

If you discover any security related issues, please email yo@robin.se instead of using the issue tracker.

## Credits

- [All Contributors](../../contributors)

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
