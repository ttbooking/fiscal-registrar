# 1. Overview

This Laravel package intended to make, store and process 54FZ-compatible receipts accepted by the *Tax Service of Russian Federation* through *Fiscal Data Operators* (FDO, _ru_ ОФД).
For the time being it supports most of the *Fiscal Data Format* (FDF, _ru_ ФФД) 1.05 tags and *ATOL Online* service API v4 via supplemented driver.

# 2. Requirements

* PHP 8.2 or greater;
* Laravel Framework 10 or greater;
* Highly recommended: RDBMS with JSON column support;
* ATOL Online & FDO contracts and connection requisites (not needed for testing purposes).

# 3. Installation

Installation process is simple and straightforward as for almost any Laravel package.

You can install Fiscal Registrar package via Composer:
`composer require ttbooking/fiscal-registrar`

Package service provider and facade aliases will be automatically registered in your application if package discovery is configured for your project.

Next, you'll need to publish package configuration file (required) and database migration (optional, if you need to change table name or add columns, etc):
```sh
# Publish configuration
php artisan vendor:publish --provider=TTBooking\FiscalRegistrar\FiscalRegistrarServiceProvider --tag=config

# Publish migration
php artisan vendor:publish --provider=TTBooking\FiscalRegistrar\FiscalRegistrarServiceProvider --tag=migrations

# Publish everything package-related
php artisan vendor:publish --provider=TTBooking\FiscalRegistrar\FiscalRegistrarServiceProvider
```

Then, if you've published migration, you'll need to make needed changes to it before proceeding to the next step.
In a default Laravel installation, look it up here: {<Project Root>/database/migrations/blahblah_receipts.php}.

Finally, you should apply database migration, which will create table for receipt storage:
`php artisan migrate`

# 4. Configuration

## 4.1. Using customizable receipt model

First, if you made changes to the database migration (especially table name) during package installation, this step is mandatory.
Otherwise, it is completely optional.
Create Eloquent model in your application's Models namespace (or somewhere else), say (`App\Models\Receipt`), and extend it from
the package supplemented default implementation (`TTBooking\FiscalRegistrar\Models\Receipt`). Final result will look like this:
```php
<?php

namespace App\Models;

use TTBooking\FiscalRegistrar\Models\Receipt as BaseReceipt;

class Receipt extends BaseReceipt
{
    protected $table = 'custom_receipts';
}
```
Note the `$table` property - it should point to the actual receipt table.
Of course, you may customize this model as you wish, just don't break what already works :)

## 4.2. Configuring options

You will find Fiscal Registrar configuration file (published in the installation step) here: {<Project Root>/config/fiscal-registrar.php}.

Main options are:
* `path` - path onto (under) which the routes (API & UI) will be mounted (grouped).
* `middleware` - middleware to append to the package's routes.
* `model` - register your custom receipt model here, and it will replace default model globally (package-wise).
* `connection` - default connection instance with its own driver and configuration options. (Better use environment variable, see below)
* `connections` - per-connection configurations, will be passed to the driver instances.
* `connections.<name>.driver` - driver used for connection; if omitted, will try to resolve driver by connection's name.
* `connections.<name>.callback` - callback URL, if supported by the provider and driver.
By default, callback URL is autogenerated and doesn't need to be explicitly defined.
In order to disable this behavior, you may define callback URL manually or disable callback at all, passing (assigning) `false` (in)to the option.
* `connections.<name>.*` - other driver-specific options.

Also, there is `FR_CONNECTION` environment variable, which you may use in your project's .env file to choose default connection.

# 5. General Usage

The most straightforward way of usage in a client code is dependency injection using `TTBooking\FiscalRegistrar\Contracts\FiscalRegistrar` interface.
This interface has two methods:

`public function register(Operation $operation, string $externalId, Receipt $data): string`

Register receipt described with `Receipt` DTO (`$data` argument) using `$operation` registration method.
You must also provide unique identifier of the receipt in your system using `$externalId` argument.
In the simplest case you may use `Str::uuid()` helper function to generate this identifier.
The return value of this method will be the unique receipt identifier on the provider side.

The `Receipt` DTO mostly resembles similar structure described in ATOL Online documentation.

`public function report(string $id): ?Result`

Check out current receipt status using identifier, returned by `register` method.

# 6. Storage-agnostic FiscalRegistrar facade

# 7. Receipt facade w/ fluent interface

# 8. receipt() helper function

# 9. Web API counterparts

This package also may work as a microservice via its RESTful API.

# 10. Available console commands

# 11. Web UI (separate package, take a look)

# 12. Extending functionality / Writing drivers
