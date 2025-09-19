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

# 4️⃣ Copiar o projeto
WORKDIR /var/www/html
COPY . .

# 5️⃣ Instalar dependências do Laravel
RUN composer install --no-dev --optimize-autoloader

# 6️⃣ Expor a porta
EXPOSE 8080

# 7️⃣ Comando de start: migrations + seed + serve
CMD php artisan migrate --force --seed && php artisan serve --host=0.0.0.0 --port=8080
