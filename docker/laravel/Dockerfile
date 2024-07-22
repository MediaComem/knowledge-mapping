FROM php:8.2-fpm

ARG LARAVEL_UID
ARG LARAVEL_GID

WORKDIR /var/www

# Install system dependencies
RUN apt-get update && apt-get install -y \
  rsync \
  git \
  curl \
  libpng-dev \
  libonig-dev \
  libxml2-dev \
  zip \
  unzip \
  libzip-dev \
  supervisor \
  redis-server \
  tzdata

# Install PHP extensions
RUN docker-php-ext-install \
  mbstring \
  exif \
  pcntl \
  bcmath \
  gd \
  zip

# Set ownership
RUN addgroup --system --gid $LARAVEL_GID laravel && \
  adduser --system --ingroup laravel --uid $LARAVEL_UID laravel && \
  chown -R laravel:laravel /var/www

# Change user
USER laravel

# Set working directory
WORKDIR /var/www

# Get latest Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

COPY --chown=laravel:laravel ./front .
COPY --chown=laravel:laravel ./data /var/www/data
COPY --chown=laravel:laravel ./python /var/www/python

RUN composer require "laravel/installer" && \
  composer install && \
  php artisan config:clear

COPY ./docker/laravel/supervisord.conf /etc/supervisor/conf.d/
ENTRYPOINT ["/usr/bin/supervisord", "-c", "/etc/supervisor/conf.d/supervisord.conf"]