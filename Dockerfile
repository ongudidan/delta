FROM vaultke/php8-fpm-nginx

# Copy application files
COPY . /var/www/html

# Set the working directory
WORKDIR /var/www/html

# Install zip extension using apk (Alpine package manager)
RUN apk --no-cache add libzip-dev zip \
    && docker-php-ext-install zip

# Set permissions
RUN chmod -R 777 /var/www/html
