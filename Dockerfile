FROM php:7.2-apache 
RUN apt-get update -y && apt-get install -y libmcrypt-dev openssl git zlib1g-dev zip unzip libicu-dev g++ libpcre3-dev
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer
RUN pecl install -o -f redis &&  rm -rf /tmp/pear &&  docker-php-ext-enable redis
RUN docker-php-ext-configure intl
RUN docker-php-ext-install pdo pdo_mysql mbstring zip intl
RUN a2enmod rewrite
RUN a2enmod headers
WORKDIR /var/www/html
COPY src/ /var/www/html
RUN chmod -R 777 storage/
#RUN composer install
EXPOSE 80
