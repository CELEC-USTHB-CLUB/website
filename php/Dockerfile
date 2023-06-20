FROM php:8.2-fpm
RUN docker-php-ext-install pdo pdo_mysql
RUN apt-get update && apt-get install nano -y && \
    apt-get install openssl -y && \
    apt-get install libssl-dev -y && \
    apt-get install wget -y && \
    apt-get install git -y && \
    apt-get install procps -y && \
    apt-get install htop -y && \
    apt-get upgrade -y
RUN apt-get install -y --no-install-recommends \
    supervisor
RUN apt-get install build-essential
ENV CFLAGS="$CFLAGS -D_GNU_SOURCE"
RUN docker-php-ext-install sockets
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer
RUN apt-get install libz-dev libzip-dev -y
RUN apt-get update && apt-get install -y \
    zlib1g-dev \
    libzip-dev \
    unzip
RUN docker-php-ext-install zip

ENV PHPREDIS_VERSION 5.3.7

RUN mkdir -p /usr/src/php/ext/redis \
    && curl -L https://github.com/phpredis/phpredis/archive/$PHPREDIS_VERSION.tar.gz | tar xvz -C /usr/src/php/ext/redis --strip 1 \
    && echo 'redis' >> /usr/src/php-available-exts \
    && docker-php-ext-install redis
RUN apt-get install libonig-dev -y
RUN docker-php-ext-install mbstring
RUN apt-get install libpng-dev -y
RUN docker-php-ext-install gd
RUN docker-php-ext-enable redis
RUN docker-php-ext-install exif
RUN apt-get install libfreetype6-dev -y
RUN apt-get install libjpeg-dev -y
RUN docker-php-ext-configure gd --enable-gd --with-freetype --with-jpeg
RUN docker-php-ext-install gd

RUN apt-get update && apt-get install -y \
    libmagickwand-dev --no-install-recommends
RUN mkdir -p /usr/src/php/ext/imagick; \
    curl -fsSL https://github.com/Imagick/imagick/archive/06116aa24b76edaf6b1693198f79e6c295eda8a9.tar.gz | tar xvz -C "/usr/src/php/ext/imagick" --strip 1; \
    docker-php-ext-install imagick;
RUN docker-php-ext-enable imagick
RUN apt-get autoremove -y && rm -rf /var/lib/apt/lists/*
RUN touch /usr/local/etc/php/conf.d/uploads.ini \
    && echo "upload_max_filesize = 10M;" >> /usr/local/etc/php/conf.d/uploads.ini
RUN apt update && \
    apt install -y mariadb-client