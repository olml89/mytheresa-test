<p align="center"><a href="https://www.mytheresa.com/" target="_blank"><img src="https://github.com/olml89/mytheresa-test/blob/main/php-fpm/public/img/mytheresa-logo-freelogovectors.net_.png" width="400" alt="Mytheresa"></a></p>

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
