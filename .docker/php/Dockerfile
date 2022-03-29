FROM php:7.4-fpm

RUN apt-get update \
    && apt-get install -y --no-install-recommends nano vim curl debconf subversion git apt-transport-https apt-utils libpng-dev \
    build-essential locales acl mailutils wget zip unzip \
    gnupg gnupg1 gnupg2 libxml2-dev libfontconfig1 libxrender1 libjpeg-dev libxext6 libzip-dev libonig-dev libc-client-dev libkrb5-dev libmagickwand-dev \
    && rm -r /var/lib/apt/lists/* \
    && pecl install imagick

RUN docker-php-ext-configure imap --with-kerberos --with-imap-ssl

RUN docker-php-ext-install bcmath gd pdo pdo_mysql zip pcntl soap imap mbstring
RUN docker-php-ext-enable imagick
COPY php.ini /usr/local/etc/php/php.ini
COPY php-fpm-pool.conf /etc/php/pool.d/www.conf



RUN curl -sSk https://getcomposer.org/installer | php -- --disable-tls && \
   mv composer.phar /usr/local/bin/composer

RUN groupadd dev -g 999
RUN useradd dev -g dev -d /home/dev -m

RUN rm -rf /var/lib/apt/lists/*
RUN echo "en_US.UTF-8 UTF-8" > /etc/locale.gen && \
    echo "fr_CA.UTF-8 UTF-8" >> /etc/locale.gen && \
    locale-gen

WORKDIR /home/wwwroot/

EXPOSE 9000
CMD ["php-fpm"]
