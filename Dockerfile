# Use the official PHP 8.1 with Apache base image
FROM php:8.1-apache

# Update package information
RUN apt-get update

# Install necessary dependencies for PHP extensions
RUN apt-get install -y \
    git \
    zlib1g-dev \
    libzip-dev \
    libicu-dev \
    libfreetype6-dev \
    libjpeg62-turbo-dev \
    libpng-dev \
    libonig-dev  # Dependency for mbstring

# Install intl extension
RUN docker-php-ext-install intl

# Install zip extension
RUN docker-php-ext-install zip

# Install mbstring extension (with the required dependencies)
RUN docker-php-ext-install mbstring

# Install PDO MySQL extension
RUN docker-php-ext-install pdo_mysql

# Install MySQLi extension
RUN docker-php-ext-install mysqli

# Enable the MySQLi extension
RUN docker-php-ext-enable mysqli

# Install and configure GD with FreeType and JPEG support
RUN docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install gd

# Install additional PHP extensions (fileinfo)
RUN docker-php-ext-install fileinfo

# Install Composer, a PHP dependency manager
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

# Clean up unnecessary package data to reduce image size
RUN apt-get clean && rm -rf /var/lib/apt/lists/*
