# Use the official PHP image with Apache
FROM php:8.3-apache

# Install required PHP extensions and dependencies
RUN apt-get update && apt-get install -y \
    libpng-dev \
    libjpeg-dev \
    libfreetype6-dev \
    libonig-dev \
    libzip-dev \
    libicu-dev \
    unzip \
    git \
    libpq-dev \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install pdo pdo_pgsql gd zip \
    && docker-php-ext-install intl
    
# Allow Composer to run as root (superuser)
ENV COMPOSER_ALLOW_SUPERUSER=1

# Install Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer
RUN composer clear-cache

# Set the working directory
WORKDIR /var/www/html

# Copy the Symfony application
COPY . /var/www/html

# Ensure wait-for-it.sh is executable
RUN chmod +x /var/www/html/wait-for-it.sh

# Modify Apache configuration to set the DocumentRoot to /public
RUN sed -i 's|DocumentRoot /var/www/html|DocumentRoot /var/www/html/public|g' /etc/apache2/sites-available/000-default.conf

# Install Symfony dependencies (composer install)
RUN composer install --no-interaction --prefer-dist --optimize-autoloader

# Set appropriate permissions for the cache and log directories
RUN mkdir -p var/cache var/logs var/sessions var/storage \
    && chown -R www-data:www-data var/cache var/logs var/sessions var/storage \
    && chmod -R 777 var/cache var/logs var/sessions var/storage

# Clear the project cache and compile the assets
RUN php bin/console cache:clear
RUN php bin/console cache:warmup
RUN php bin/console importmap:install

# Enable Apache mod_rewrite
RUN a2enmod rewrite && service apache2 restart

# Expose port 80
EXPOSE 80

# Copy the entrypoint script
COPY entrypoint.sh /usr/local/bin/entrypoint.sh
RUN chmod +x /usr/local/bin/entrypoint.sh
COPY wait-for-it.sh /usr/local/bin/wait-for-it.sh
RUN chmod +x /usr/local/bin/wait-for-it.sh

# Set the entrypoint script
ENTRYPOINT ["/usr/local/bin/entrypoint.sh"]

# Start Apache in the foreground
CMD ["apache2-foreground"]