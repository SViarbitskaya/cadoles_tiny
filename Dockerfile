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

# Install Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Set the working directory
WORKDIR /var/www/html

# Copy the Symfony application
COPY . /var/www/html

# Modify Apache configuration to set the DocumentRoot to /public
RUN sed -i 's|DocumentRoot /var/www/html|DocumentRoot /var/www/html/public|g' /etc/apache2/sites-available/000-default.conf

# Install Symfony dependencies
RUN composer install --no-scripts --no-autoloader

# Set appropriate permissions for the cache and log directories
RUN mkdir -p var/cache var/logs var/sessions var/storage \
    && chown -R www-data:www-data var/cache var/logs var/sessions var/storage \
    && chmod -R 777 var/cache var/logs var/sessions var/storage

# Enable Apache mod_rewrite
RUN a2enmod rewrite && service apache2 restart

# Expose port 80
EXPOSE 80

# Clear the project cache and compile the assets
RUN php bin/console cache:clear --no-warmup && \
    php bin/console cache:warmup && \
    php bin/console importmap:install
    # php bin/console assets:install && \
    # php bin/console asset-map:compile \


# Start Apache in the foreground
CMD ["apache2-foreground"]
