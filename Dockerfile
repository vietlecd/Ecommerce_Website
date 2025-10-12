# Use official PHP image with Apache
FROM php:8.2-apache

# Install dependencies and PHP extensions
RUN apt-get update && apt-get install -y \
    libpng-dev \
    libjpeg-dev \
    libfreetype6-dev \
    libzip-dev \
    default-mysql-client \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install -j$(nproc) gd pdo pdo_mysql zip \
    && a2enmod rewrite \
    && rm -rf /var/lib/apt/lists/*

# PHP configuration
RUN echo "upload_max_filesize = 10M" > /usr/local/etc/php/conf.d/uploads.ini

# Set working directory and copy application files
WORKDIR /var/www/html
COPY . /var/www/html
RUN chown -R www-data:www-data /var/www/html && chmod -R 775 /var/www/html

# Create bin command shortcut
COPY docker-bin /usr/local/bin/bin
RUN chmod +x /usr/local/bin/bin

# Apache config for .htaccess
RUN echo '<Directory /var/www/html>\n\
    AllowOverride All\n\
    Require all granted\n\
</Directory>' > /etc/apache2/conf-available/allowoverride.conf && \
    a2enconf allowoverride

# Expose port 80
EXPOSE 80

CMD ["apache2-foreground"]
