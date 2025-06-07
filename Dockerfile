# ใช้ PHP 8.2 FPM image
FROM php:8.2-fpm

# ติดตั้ง dependencies และติดตั้ง PHP zip extension
RUN apt-get update && apt-get install -y libpng-dev libjpeg-dev libfreetype6-dev zip git libzip-dev
RUN docker-php-ext-configure zip
RUN docker-php-ext-install gd pdo pdo_mysql zip

# ตั้งค่า working directory
WORKDIR /var/www

# คัดลอกไฟล์โปรเจกต์จากเครื่องของคุณไปที่ container
COPY . .

# ติดตั้ง Composer
RUN curl -sS https://getcomposer.org/installer | php
RUN mv composer.phar /usr/local/bin/composer

# ติดตั้ง dependencies ด้วย Composer
RUN composer install --no-dev --optimize-autoloader

ENV JWT_SECRET=7LCWv6r3eRSZl2E78ExaHUbjP7RoFzuK7jgwxKaf27AlwmsbJLFOcFsI7sURIEcl
ENV AUTH_GUARD=api


ENV DB_CONNECTION=mysql
ENV DB_HOST=mysql-193375-0.cloudclusters.net
ENV DB_PORT=19750
ENV DB_DATABASE=
ENV DB_USERNAME=
ENV DB_PASSWORD=

EXPOSE 8000

# รัน Laravel ด้วย php artisan serve
CMD php artisan serve --host=0.0.0.0 --port=${PORT}

