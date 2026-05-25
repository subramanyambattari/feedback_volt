FROM php:8.2-cli

# Install system packages
RUN apt-get update && apt-get install -y \
    git \
    unzip \
    curl \
    libzip-dev \
    zip \
    nodejs \
    npm \
    libssl-dev \
    pkg-config

# Install PHP extensions
RUN docker-php-ext-install zip

# Install MongoDB extension
RUN pecl install mongodb-1.21.0 \
    && docker-php-ext-enable mongodb

# Install Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Set working directory
WORKDIR /app

# Copy project files
COPY . .

# Install Composer dependencies
RUN composer install --no-dev --optimize-autoloader

# Install Node dependencies
RUN npm install

# Build frontend assets
RUN npm run build

# Add entrypoint script and make it executable
COPY docker-entrypoint.sh /usr/local/bin/docker-entrypoint.sh
RUN chmod +x /usr/local/bin/docker-entrypoint.sh

# remove build-time config caching so runtime env vars are respected

# Expose Render port
EXPOSE 10000

# Use entrypoint so we can cache config using runtime env vars
ENTRYPOINT ["/usr/local/bin/docker-entrypoint.sh"]
CMD ["php", "artisan", "serve", "--host=0.0.0.0", "--port=10000"]