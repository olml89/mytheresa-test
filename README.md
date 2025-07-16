<p align="center"><a href="https://www.mytheresa.com/" target="_blank"><img src="https://github.com/olml89/mytheresa-test/blob/main/php-fpm/public/img/mytheresa-logo-freelogovectors.net_.png" width="400" alt="Mytheresa"></a></p>
[![CI](https://github.com/olml89/mytheresa-test/actions/workflows/ci.yml/badge.svg)]

This is an implementation of technical test for a senior developer role at 
[Mytheresa](https://www.mytheresa.com/), with the following
[technical specifications](https://github.com/olml89/mytheresa-test/blob/main/doc/promotions-assigment-mytheresa-250716_090156.pdf).

# Implementation details

The basic structure of the application is a dockerized environment with three containers.

`nginx` acts as the http server and reverse proxy, and communicates through fastcgi with
`php-fpm`, that holds the php application based on a `php-fpm:8.4-alpine` base image to keep
it lean. In the build phase we can choose a prod or a dev build, the main difference being that
the dev build has `xdebug` installed to enable debugging and code coverage testing inside the container,
while the prod one has not. Also, in the prod build, the codebase is copied directly into the image to 
include all necessary files and ensure reproducible deployments. 
In contrast, in the dev build, the files are mounted into the container using a Docker volume, 
allowing live code reloading as we are developing.

`postgres` has been chosen as the persistence layer, taking into account the requirement to 
support large loads of data and possibly high traffic.

# Architectural decisions

I have tried to follow mostly a DDD pattern, not tied to any framework in particular.

In the infrastructure layer, I decided to use
[php-di](https://github.com/PHP-DI/PHP-DI)
as the injection container,
[doctrine-orm](https://github.com/doctrine/orm) 
as the data abstraction layer, symfony components to handle input and output 
(
[symfony/http-kernel](https://github.com/symfony/http-kernel), 
[symfony/console](https://github.com/symfony/console)).

I have implemented a custom `EntityManagerProvider` to automatically resolve an instance of the Doctrine entity manager
with my custom types. Both the types and the mappings will be resolved with no configuration involved
thanks to `EntityIterator` and `TypeIterator`, that automatically inject mappings and types to the entity manager.
There's also a `FixtureIterator` that does the same with fixtures.

# Installation

## Dependencies

On production, the dependencies are built during the build phase and already deployed
into the container. On development, after setting the containers up, we have
to log into the `php-fpm` container and run:

```bash
composer install
```

## Database

On production, the initial deployment of the database should be done using migrations
to ensure a reproducible database state. On development, we log into the `php-fpm`
container and run:

```bash
php bin/doctrine.php orm:schema:create
```

This will create the database schema using our Doctrine entities mappings. To populate the database with some data
I have implemented a `ProductFixture`. This command will run that and also all the other fixtures present on the system:

```bash
php bin/console.php load:fixtures
```

## Development

I have
[phpstan](https://github.com/phpstan/phpstan)
and 
[laravel/pint](https://github.com/laravel/pint) 
as a part of my development and CI workflow. This way I make sure my codebase is `PSR-12` compliant
and is less prone to bugs.

To run phpstan:

```bash
php vendor/bin/phpstan --configuration=phpstan.neon
make phpstan
```

The first one has to be executed from inside `php-fpm`, the second one can be executed from the
root of the project. Passing the `--no-progress` flag (or `ci` option in the case of the Make recipe)
will execute it faster.

To run pint:

```bash
php vendor/bin/pint --config=pint.json
make pint
```

The first one has to be executed from inside `php-fpm`, the second one can be executed from the
root of the project. Passing the `--test` flag (or `ci` option in the case of the Make recipe)
will only check for errors; omitting it will actually **lint** the code.
