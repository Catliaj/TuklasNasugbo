# Use PHP with Apache
FROM php:8.2-apache

# Install PHP extensions
RUN docker-php-ext-install mysqli pdo pdo_mysql

# Enable Apache mod_rewrite (needed by CodeIgniter)
RUN a2enmod rewrite

# Set up Apache DocumentRoot to the "public" folder
WORKDIR /var/www/html
COPY . /var/www/html

# Configure Apache to use /public as root
RUN sed -i 's!/var/www/html!/var/www/html/public!g' /etc/apache2/sites-available/000-default.conf

EXPOSE 80
