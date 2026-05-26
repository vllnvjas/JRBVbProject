# FROM php:8.2-cli
FROM php:8.4-fpm

RUN apt-get update && apt-get install -y \
git unzip curl libzip-dev zip libpng-dev \
&& docker-php-ext-install pdo pdo_mysql zip

COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

WORKDIR /var/www

COPY . .

RUN composer install --no-dev --optimize-autoloader

RUN cp .env.example .env \
	&& printf '\nSESSION_DRIVER=file\nHASH_DRIVER=bcrypt\nCACHE_STORE=file\n' >> .env

RUN php artisan key:generate

EXPOSE 10000

# CMD php artisan serve --host=0.0.0.0 --port=10000
CMD sh -c "php artisan migrate --force && php artisan db:seed --class=UserSeeder --force && php -S 0.0.0.0:${PORT:-10000} -t public server.php"