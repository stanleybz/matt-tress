FROM php:fpm
RUN apt-get update && apt-get install -y libmcrypt-dev

# lumen packages
RUN apt-get install -y php5-memcached \
    && docker-php-ext-install mcrypt mbstring tokenizer mysqli pdo_mysql \
    && apt-get install -y libz-dev libmemcached-dev \
    && pecl install memcached \
    && echo extension=memcached.so >> /usr/local/etc/php/conf.d/memcached.ini

# Install git and composer
RUN apt-get install -y git
RUN php -r "copy('https://getcomposer.org/installer', 'composer-setup.php');"
RUN php composer-setup.php
RUN php -r "unlink('composer-setup.php');"
RUN mv composer.phar /usr/local/bin/composer

COPY ./app /var/www/html/app
WORKDIR /var/www/html/app

RUN composer install --prefer-source --no-interaction
