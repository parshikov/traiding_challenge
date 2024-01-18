ARG PHP_VER

FROM php:$PHP_VER-fpm-alpine
# Install dev packages
RUN	apk update && \
	apk upgrade && \
	apk add --upgrade --no-cache \
		$PHPIZE_DEPS \
		openssl-dev \
		linux-headers \
		# postgresql
		postgresql-dev \
		postgresql \
		# zip
		zlib-dev libzip-dev \
		libzip \
		git && \
# Install modules
	docker-php-ext-install pdo_pgsql zip pcntl && \
	PHP_OPENSSL=yes pecl install redis xdebug && \
	docker-php-ext-enable redis xdebug && \
# Cleanup after install
	apk del \
		$PHPIZE_DEPS \
		openssl-dev \
		linux-headers \
		postgresql-dev \
		zlib-dev libzip-dev

# Install composer
RUN	php -r "copy('https://getcomposer.org/installer', 'composer-setup.php');" && \
	php composer-setup.php --install-dir=/usr/bin --filename=composer && \
	rm composer-setup.php

EXPOSE 9000
WORKDIR /var/www
