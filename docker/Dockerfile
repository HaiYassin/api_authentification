FROM php:7.4-apache

# SERVERNAME = LOCALHOST
RUN echo "ServerName localhost" >> /etc/apache2/apache2.conf

# CLEAN & INSTALL UTILS
RUN apt-get clean && apt-get update \
    && apt-get install -y --no-install-recommends locales apt-utils git bash wget curl libicu-dev g++ libpng-dev libxml2-dev libzip-dev libonig-dev libxslt-dev;

# SET LOCALES
RUN echo "en_US.UTF-8 UTF-8" > /etc/locale.gen && \
    echo "fr_FR.UTF-8 UTF-8" >> /etc/locale.gen && \
    locale-gen

# COMPOSER INSTALL
RUN php -r "copy('https://getcomposer.org/installer', 'composer-setup.php');" &&  php composer-setup.php && php -r "unlink('composer-setup.php');" && mv composer.phar /usr/local/bin/composer

# PHP EXTENSIONS
RUN docker-php-ext-configure intl
RUN docker-php-ext-install pdo pdo_mysql gd opcache intl zip calendar dom mbstring zip gd xsl json
RUN pecl install apcu && docker-php-ext-enable apcu

# SYMFONY CLI
RUN wget https://get.symfony.com/cli/installer -O - | bash && mv /root/.symfony/bin/symfony /usr/local/bin/symfony

WORKDIR /var/www
