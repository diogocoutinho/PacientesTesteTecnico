# Use a imagem base do PHP 8.0-FPM
FROM php:8.3-fpm

ENV COMPOSER_ALLOW_SUPERUSER=1

# Define o diretório de trabalho no container
WORKDIR /var/www/html

# Atualiza o gerenciador de pacotes e instala dependências necessárias
RUN apt-get update \
    && apt-get install -y \
        git \
        unzip \
        libpq-dev \
        libonig-dev \
        libxml2-dev \
        libzip-dev \
        supervisor \
        libpng-dev \
        libjpeg-dev \
        libfreetype6-dev \
        libwebp-dev \
    && docker-php-ext-configure gd --with-freetype --with-jpeg --with-webp \
    && docker-php-ext-install -j$(nproc) \
        pdo \
        pdo_pgsql \
        mbstring \
        xml \
        zip \
        gd \
        pcntl \
    && pecl install redis \
    && docker-php-ext-enable redis

# Instala o Composer globalmente
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

# Copia o conteúdo do diretório do host para o diretório de trabalho do container
COPY . /var/www/html
RUN mkdir /var/log/redis
RUN mkdir /var/log/nginx
RUN mkdir /var/log/php
RUN mkdir -p /var/log/horizon

# Instala as dependências do Node.js (opcional, se estiver usando recursos de frontend)
RUN apt-get install -y nodejs npm

# Copia os arquivos de configuração do Supervisor para o diretório correto
COPY supervisor/redis.conf /etc/redis/redis.conf
COPY supervisor/supervisord.conf /etc/supervisor/conf.d/supervisord.conf
COPY nginx.conf /etc/nginx/nginx.conf

# Instala as dependências do Composer
RUN #composer install
RUN #npm install && npm run dev

# Define a porta em que o contêiner estará escutando
EXPOSE 8000:9000

# Entrypoint personalizado para iniciar o Supervisor
ENTRYPOINT ["/usr/bin/supervisord", "-n", "-c", "/etc/supervisor/conf.d/supervisord.conf"]
