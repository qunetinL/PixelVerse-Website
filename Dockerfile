FROM php:8.2-apache

# Installation des dépendances système
RUN apt-get update && apt-get install -y \
    libzip-dev \
    zip \
    unzip \
    git \
    libssl-dev \
    && docker-php-ext-install pdo_mysql zip

# Installation de l'extension MongoDB via PECL
RUN pecl install mongodb \
    && docker-php-ext-enable mongodb

# Activation du module rewrite d'Apache
RUN a2enmod rewrite
