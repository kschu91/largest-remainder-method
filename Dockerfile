FROM php:8.0-cli
COPY . /usr/src/largest-remainder-method
WORKDIR /usr/src/largest-remainder-method

RUN apt-get update && apt-get -y upgrade && apt-get -y install libzip-dev
RUN pecl install zip; \
        docker-php-ext-enable zip;

RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer
RUN composer -n --prefer-source install --dev