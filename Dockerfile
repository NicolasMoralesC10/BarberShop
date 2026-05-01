FROM php:8.2-apache

RUN apt-get update && apt-get install -y \
    default-mysql-client netcat-openbsd \
    libpng-dev libjpeg-dev libwebp-dev libfreetype6-dev \
    zip unzip \
    && apt-get clean && rm -rf /var/lib/apt/lists/*

RUN docker-php-ext-configure gd --with-freetype --with-jpeg --with-webp \
    && docker-php-ext-install gd pdo pdo_mysql mysqli

RUN a2enmod rewrite

WORKDIR /var/www/html

COPY . .

RUN mkdir -p uploads/servicios \
    && chown -R www-data:www-data uploads \
    && chmod -R 775 uploads

COPY docker/apache.conf /etc/apache2/sites-available/000-default.conf
COPY docker/entrypoint.sh /usr/local/bin/entrypoint.sh
RUN chmod +x /usr/local/bin/entrypoint.sh

ENTRYPOINT ["/usr/local/bin/entrypoint.sh"]
