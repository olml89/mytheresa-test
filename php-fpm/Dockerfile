FROM php:8.4-fpm-alpine AS base

# Set the container working directory
WORKDIR /mytheresa-test/php-fpm

# Install PHP extensions
COPY --from=mlocati/php-extension-installer /usr/bin/install-php-extensions /usr/local/bin/
RUN install-php-extensions pgsql pdo_pgsql;

FROM base AS prod

# Copy the codebase to the container
COPY php-fpm/ ./

# Install composer
# - curl, bash, composer: needed to install dependencies during the build phase, don't keep it in the container
RUN apk add --no-cache curl bash && \
    curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

# Install PHP dependencies (without require-dev, optimized autoload)
RUN composer install --no-dev --optimize-autoloader --no-interaction --no-progress

# Clear composer, curl, bash and extensions installer
RUN rm /usr/local/bin/composer && \
    apk del curl bash && \
    rm /usr/local/bin/install-php-extensions

FROM base AS dev

# Install composer and xdebug and clear extensions installer
RUN install-php-extensions xdebug @composer && rm /usr/local/bin/install-php-extensions;

# Copy xdebug configuration file
COPY php-fpm/docker-php-ext-xdebug.ini /usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini

# Override xdebug default port with the environment provided one
ARG XDEBUG_PORT=9003
RUN printf '\nxdebug.client_port=%s\n' ${XDEBUG_PORT} >> /usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini;
