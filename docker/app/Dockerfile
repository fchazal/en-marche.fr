FROM php:7.1-apache

RUN buildDeps=" \
        build-essential \
        libmcrypt-dev \
        libgcrypt11-dev \
        libpng12-dev \
        libfreetype6-dev \
        libjpeg62-turbo-dev \
        libgmp-dev \
        libicu-dev \
        zlib1g-dev \
    " \
    && apt-get update \
    && apt-get install -y --no-install-recommends \
        $buildDeps \
        vim \
        libicu52 \
        libmcrypt4 \
        libjpeg62-turbo \
        libfreetype6 \
    && ln -s /usr/include/x86_64-linux-gnu/gmp.h /usr/include/gmp.h \
    && docker-php-ext-configure intl \
    && docker-php-ext-configure gmp --with-gmp=/usr/include/x86_64-linux-gnu/ \
    && docker-php-ext-install gmp mcrypt mbstring intl pdo_mysql zip opcache \
    && docker-php-ext-configure gd --with-freetype-dir=/usr/include/ --with-jpeg-dir=/usr/include/ \
    && docker-php-ext-install gd bcmath \
    && pecl install apcu-5.1.7 && docker-php-ext-enable apcu --ini-name 10-docker-php-ext-apcu.ini \
    && pecl install apcu_bc-1.0.3 && docker-php-ext-enable apc --ini-name 20-docker-php-ext-apcu.ini \
    && rm /usr/include/gmp.h \
    && apt-get purge -y --auto-remove -o APT::AutoRemove::RecommendsImportant=false -o APT::AutoRemove::SuggestsImportant=false $buildDeps \
    && apt-get clean \
    && rm -rf /var/lib/apt/lists/* /tmp/* /var/tmp/*

# Apache conf
COPY vhost.conf /etc/apache2/sites-available/docker.conf
RUN mkdir -p /www/web \
    && a2ensite docker \
    && a2enmod rewrite ssl \
    && service apache2 restart

# PHP conf
COPY php.ini $PHP_INI_DIR/conf.d/z_docker.ini

# Override the image ENTRYPOINT to add some logic
COPY launcher.sh /usr/local/bin/launcher
RUN chmod +x /usr/local/bin/launcher
ENTRYPOINT ["launcher"]

# Expose standard ports
EXPOSE 80

# Directory of the application
WORKDIR /www

CMD ["apache2-foreground"]
