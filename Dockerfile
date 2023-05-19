# Use a PHP 8.1 base image with Apache
FROM php:8.1-apache

# Install system dependencies
RUN apt-get update && apt-get install -y \
    git \
    zip \
    unzip \
    curl \
    libpng-dev \
    libonig-dev \
    libxml2-dev \
    libzip-dev

# Clear cache
RUN apt-get clean && rm -rf /var/lib/apt/lists/*

# Install PHP extensions
RUN docker-php-ext-install pdo_mysql mbstring exif pcntl bcmath gd zip

# Enable Apache mod_rewrite
RUN a2enmod rewrite

# Copy Laravel project files to the container
COPY . /var/www/html

# Set the working directory to the Laravel project root
WORKDIR /var/www/html

# Install Composer and dependencies
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer
RUN composer install --no-dev

# Set permissions for Laravel storage directory
RUN chmod -R 777 storage

# Set the environment variables
ENV APACHE_DOCUMENT_ROOT /var/www/html/public

#RUN rm -r /etc/apache2/apache2.conf
# Copy Apache virtual host configuration file
COPY apache/apache.conf /etc/apache2/sites-available/000-default.conf
#COPY apache/apache2.conf /etc/apache2/apache2.conf

# Expose port 80 for Apache
EXPOSE 80

# Start Apache web server
CMD ["/usr/sbin/apache2ctl", "-D", "FOREGROUND"]