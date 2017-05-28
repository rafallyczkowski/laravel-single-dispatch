# Single Dispatch
Extension for laravel dispatcher that do not allow to proceed same jobs on queues.
Motivation for this feature is to not overuse resources while specific job is still waiting to be processed another one should not be queued.

## Features

* Allows to catch and ignore duplicated jobs

## Requirements

Installed 
https://laravel.com/docs/5.4/queues

## Installation

Require the `mbm-rafal/laravel-single-dispatch` package in your composer.json and update your dependencies.

    $ composer require mbm-rafal/laravel-single-dispatch
    $ composer update

Publish migration files

```
$ php artisan vendor:publish --provider="MBM\Bus\BusServiceProvider" --force
```

Run migrations

```
$ php artisan migrate
```

## Usage

To use this extension you have to update resource resolved by provided by `Dispatcher::class` to do 
so, simply switch `BusServiceProvider` in `app/config.php` 

```php
// Illuminate\Bus\BusServiceProvider::class,
\MBM\Bus\BusServiceProvider::class,
```

You have to apply code to Queue events in `AppServiceProvider`
```php
# Manage processed jobs
Queue::after(function (JobProcessed $event) {
    app(\MBM\Bus\Dispatcher::class)->unregister($event->job);
});

Queue::failing(function (JobFailed $event) {
    app(\MBM\Bus\Dispatcher::class)->unregister($event->job);
});
```
        
If you want to allow job to be duplicated add interface to Job class definition
```php
use Illuminate\Contracts\Queue\ShouldQueue;
use \MBM\Bus\AllowDuplicates
 
class CustomJob implements ShouldQueue, AllowDuplicates
{
    // Code
}
```

## License

Released under the MIT License, see LICENSE.
