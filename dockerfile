# 1️⃣ Base PHP com FPM
FROM php:8.2-fpm

# 2️⃣ Instalar dependências do sistema e PHP
RUN apt-get update && apt-get install -y \
    git \
    unzip \
    libzip-dev \
    && docker-php-ext-install pdo_mysql zip

# 3️⃣ Instalar Composer
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

# 4️⃣ Copiar o projeto para o container
WORKDIR /var/www/html
COPY . .

# 5️⃣ Instalar dependências do Laravel
RUN composer install --no-dev --optimize-autoloader

# 6️⃣ Expor a porta que o Railway vai usar
EXPOSE 8080

# 7️⃣ Start Command que roda migrate + seed + serve
CMD php artisan migrate --force && php artisan db:seed && php artisan serve --host=0.0.0.0 --port=8080
