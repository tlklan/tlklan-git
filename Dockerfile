FROM php:7.2-apache

# Copy needed files
COPY tlklan /var/www/html/tlklan
WORKDIR /var/www/html/tlklan

# Install deps
RUN apt-get update && apt-get install -yy \
        libfreetype6-dev \
        libjpeg62-turbo-dev \
        libpng-dev \
        unzip \
        git \
        libmcrypt-dev \
        && docker-php-ext-install -j$(nproc) gd pdo pdo_mysql\
        && pecl install mcrypt \
        && docker-php-ext-enable mcrypt
RUN curl https://getcomposer.org/download/1.7.3/composer.phar -o /usr/local/bin/composer && chmod 755 /usr/local/bin/composer
RUN composer install
RUN chown -R www-data:www-data .

# Configure apache
COPY provisioning/etc/apache2/sites-available/tlklan.conf /etc/apache2/sites-available/
RUN a2dissite 000-default
RUN a2ensite tlklan
RUN a2enmod rewrite expires rewrite

# Add legacy symlink
RUN mkdir -p /media/Storage && ln -s /var/www/html/tlklan/files/submissions /media/Storage/submissions
