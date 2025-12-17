# Use official PHP image with Apache
# Dockerfile

# Base image PHP
FROM php:8.2-apache

# Cài Composer (copy từ image composer chính thức)
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer


# Install dependencies and PHP extensions
# Workaround for GPG keys issue: update GPG keys and configure apt
RUN apt-get clean && \
    rm -rf /var/lib/apt/lists/* && \
    mkdir -p /etc/apt/keyrings && \
    apt-get update -o APT::Get::AllowUnauthenticated=true 2>&1 | tee /tmp/apt-update.log || true && \
    if ! grep -q "Unable to locate package" /tmp/apt-update.log; then \
        apt-get install -y --allow-unauthenticated --no-install-recommends \
        libpng-dev \
        libjpeg-dev \
        libfreetype6-dev \
        libzip-dev \
        default-mysql-client || \
        (echo "deb http://deb.debian.org/debian bookworm main" > /etc/apt/sources.list && \
         echo "deb http://deb.debian.org/debian-security bookworm-security main" >> /etc/apt/sources.list && \
         echo "deb http://deb.debian.org/debian bookworm-updates main" >> /etc/apt/sources.list && \
         apt-get update && \
         apt-get install -y --no-install-recommends \
         libpng-dev \
         libjpeg-dev \
         libfreetype6-dev \
         libzip-dev \
         default-mysql-client); \
    else \
        echo "deb http://deb.debian.org/debian bookworm main" > /etc/apt/sources.list && \
        echo "deb http://deb.debian.org/debian-security bookworm-security main" >> /etc/apt/sources.list && \
        echo "deb http://deb.debian.org/debian bookworm-updates main" >> /etc/apt/sources.list && \
        apt-get update && \
        apt-get install -y --no-install-recommends \
        libpng-dev \
        libjpeg-dev \
        libfreetype6-dev \
        libzip-dev \
        default-mysql-client; \
    fi && \
    docker-php-ext-configure gd --with-freetype --with-jpeg && \
    docker-php-ext-install -j$(nproc) gd pdo pdo_mysql zip && \
    a2enmod rewrite && \
    rm -rf /var/lib/apt/lists/* /tmp/apt-update.log

# PHP configuration
RUN echo "upload_max_filesize = 10M" > /usr/local/etc/php/conf.d/uploads.ini

# Set working directory and copy application files
WORKDIR /var/www/html
COPY . /var/www/html
RUN chown -R www-data:www-data /var/www/html && chmod -R 775 /var/www/html

# Apache config for .htaccess
RUN echo '<Directory /var/www/html>\n\
    AllowOverride All\n\
    Require all granted\n\
</Directory>' > /etc/apache2/conf-available/allowoverride.conf && \
    a2enconf allowoverride

# Expose port 80
EXPOSE 80

CMD ["apache2-foreground"]
