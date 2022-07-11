FROM php:8.1-fpm
RUN docker-php-ext-install pdo pdo_mysql
RUN apt-get update && apt-get install nano -y && \
    apt-get install openssl -y && \
    apt-get install libssl-dev -y && \
    apt-get install wget -y && \
    apt-get install git -y && \
    apt-get install procps -y && \
    apt-get install htop -y
RUN apt-get install -y --no-install-recommends \
    supervisor
ENV CFLAGS="$CFLAGS -D_GNU_SOURCE"
RUN docker-php-ext-install sockets
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer
RUN apt-get install libz-dev libzip-dev -y
RUN apt-get update && apt-get install -y \
    zlib1g-dev \
    libzip-dev \
    unzip
RUN docker-php-ext-install zip
RUN pecl install zlib zip
RUN pecl install redis
RUN apt-get install libonig-dev -y
RUN docker-php-ext-install mbstring
RUN apt-get install libpng-dev -y
RUN docker-php-ext-install gd
RUN docker-php-ext-enable redis
RUN docker-php-ext-install exif
RUN apt-get autoremove -y && rm -rf /var/lib/apt/lists/*
