# Usamos la imagen oficial
FROM php:8.2-cli

# 1. TRUCO DE VELOCIDAD:
# Descargamos un script mágico que instala extensiones ya compiladas
COPY --from=mlocati/php-extension-installer /usr/bin/install-php-extensions /usr/local/bin/

# 2. Instalamos las librerías del sistema y extensiones de PHP en un solo paso
# Agregamos: zip (para composer), grpc (para firebase), bcmath (cálculos), intl (útil)
RUN install-php-extensions zip grpc bcmath intl

# 3. Instalamos utilidades básicas (git y unzip son obligatorios para Composer)
RUN apt-get update && apt-get install -y \
    git \
    unzip

# 4. Instalamos Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Definimos carpeta de trabajo
WORKDIR /app

# Copiamos archivos
COPY . .

# 5. Instalamos dependencias de Laravel
# Nota: --ignore-platform-reqs ayuda a evitar errores falsos de versiones
RUN composer install --no-dev --optimize-autoloader --ignore-platform-reqs

# Exponemos puerto
EXPOSE 10000

# Comando de inicio
CMD php artisan serve --host 0.0.0.0 --port 10000