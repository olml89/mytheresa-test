<p align="center"><a href="https://www.mytheresa.com/" target="_blank"><img src="https://github.com/olml89/mytheresa-test/blob/main/php-fpm/public/img/mytheresa-logo-freelogovectors.net_.png" width="400" alt="Mytheresa"></a></p>
[![CI](https://github.com/olml89/mytheresa-test/actions/workflows/ci.yml/badge.svg)](https://github.com/olml89/mytheresa-test/actions/workflows/ci.yml)[![Coverage](https://codecov.io/gh/olml89/mytheresa-test/branch/main/graph/badge.svg?token=SL6ANXRH0A)](https://codecov.io/gh/olml89/mytheresa-test)

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

I have tried to follow mostly a DDD pattern, not tied to any framework in particular.

In the infrastructure layer, I decided to use
[php-di](https://github.com/PHP-DI/PHP-DI)
as the injection container,
[doctrine-orm](https://github.com/doctrine/orm) 
as the data abstraction layer, and
[slim](https://github.com/slimphp/Slim)
to handle HTTP requests and responses.
I have also used 
[symfony/console](https://github.com/symfony/console))
to make a custom command to apply fixtures to the database.

# Installation

## Dependencies

On production, the dependencies are built during the build phase and already deployed
into the container, so it should be ready to run by default. On development, after setting the containers up, we have
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

There's a single command that unifies all the previous steps, so the application is installable and ready to run
on deployment with a single command:

```bash
make install
```

There's a `.env.example` file at the root of the application and another one at the root of the `php-fpm` service.
The first one describes the needed variables to orchestrate the application and debug it on development.
The second one contains basically the skeleton of the database credentials. You have to fill the corresponding
`.env` files with those variables to run the application on development. On production not all of them are needed,
and they should be kept in a secrets vault.

## Development and testing

I have
[phpstan](https://github.com/phpstan/phpstan)
and 
[laravel/pint](https://github.com/laravel/pint) 
as a part of my development and CI workflow. This way I make sure my codebase is `PSR-12` compliant
and is less prone to bugs.

To run phpstan:

```bash
php vendor/bin/phpstan
make phpstan
```

The first one has to be executed from inside `php-fpm`, the second one can be executed from the
root of the project. Passing the `--no-progress` flag (or `ci` option in the case of the Make recipe)
will execute it faster.

To run pint:

```bash
php vendor/bin/pint
make pint
```

The first one has to be executed from inside `php-fpm`, the second one can be executed from the
root of the project. Passing the `--test` flag (or `ci` option in the case of the Make recipe)
will only check for errors; omitting it will actually lint the code.


[phpunit](https://github.com/sebastianbergmann/phpunit)
has been used during development following a TDD approach, and is used
during the CI pipeline too. I have integrated it with phpstan to ensure proper code analysis also on the tests.

To run phpunit:

```bash
php vendor/bin/phpunit
make phpunit
```

The first one has to be executed from inside `php-fpm`, the second one can be executed from the
root of the project. Passing the `--coverage` flag (or `coverage` option in the case of the Make recipe)
will output code coverage to the console. All the values that are added to the Make recipe will be parsed as
`--filter` flag:

```bash
make phpstan Test1 Test2 Test3
php vendor/bin/phpunit --filter="Test1|Test2|Test3"
```

# Logic decisions

I didn't want to use a full-fledged framework, and I chose to build the application gluing together small
pieces as I think it is more suited to show the problem-resolution skills in a technical test. Also, it is
a lot more fun.

I have implemented a custom `EntityManagerProvider` to automatically resolve an instance of the Doctrine entity manager
with my custom value object types. Both the types and the mappings will be resolved with no configuration involved
thanks to `EntityIterator` and `TypeIterator`, that automatically inject mappings and types to the entity manager
discovering the necessary classes based on their namespaces. 
There's also a `FixtureIterator` that does the same with fixtures.
I would have done the same with console commands if I had more time.

Another thing I'd have liked to do following this line of thinking is to develop real request object generic DTO's, 
similar to the ones that exist on Symfony, injecting the query or payload parameters instead of
having to recover them from the request by myself. However, at that point, I didn't want to 
spend more time developing the infrastructure than the domain logic.

I have developed a Specification pattern filtering based mechanism, that translates options filtered on the query strings
to the DoctrineCriteria used to resolve searched on the database. That was my solution to the classic DDD problem
of maintaining the separation of boundaries while allowing wide searches to be performed on a repository. The only issue
is that it only allows conditions to be successively added to the query, at the moment we need to allow OR clauses
this approach won't work unless we implement something like OData filtering on the uri (which, by the way, is a
solution I'm trying to port PHP from .NET on this 
[OData parser](https://github.com/olml89/odata-parser).

The tests I've written are mainly unit-scoped: I made a conscious decision not to test the infrastructure layer, 
as it's too complex for a technical assessment. In a real world scenario, I would have used only
battle-tested and ready available solutions instead of developing a small customised framework, 
so if I needed to implement something by myself because nothing else cut it, the infrastructural scope to 
test would be as little as possible. So, the tests have been conducted
in the Product aggregate, both in the domain and application layers, but I have also conducted an integration test
against the `/products` endpoint; however, I'm not really testing it against a real database because the specifications
of the technical assessment said the tests shouldn't require any networking or the filesystem, so I assumed it
was fine if I just mocked it.

Another thing I have omitted for the sake of simplification is logging and error handling, as I think it would be too
cumbersome too.

