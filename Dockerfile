FROM php:8.2-apache

# Install PHP extensions
RUN docker-php-ext-install mysqli pdo pdo_mysql

# Enable Apache rewrite
RUN a2enmod rewrite

# Set document root
WORKDIR /var/www/html

# Copy project files
COPY . /var/www/html

# Permissions
RUN chown -R www-data:www-data /var/www/html

EXPOSE 80
