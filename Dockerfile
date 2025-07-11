 # Build stage
 FROM node:20-alpine as node-build
 WORKDIR /app
 COPY package*.json ./
 RUN npm install
 COPY . .
 RUN npm run build

 # PHP stage
 FROM php:8.3-fpm

 # Install system dependencies
 RUN apt-get update && apt-get install -y \
     git \
     curl \
     libpng-dev \
     libonig-dev \
     libxml2-dev \
     zip \
     unzip

 # Clear cache
 RUN apt-get clean && rm -rf /var/lib/apt/lists/*

 # Install PHP extensions
 RUN docker-php-ext-install pdo_mysql mbstring exif pcntl bcmath gd

 # Get latest Composer
 COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

 # Set working directory
 WORKDIR /var/www/html

 # Copy existing application directory
 COPY . .

 # Copy built assets from node-build stage
 COPY --from=node-build /app/public/build /var/www/html/public/build

 # Install dependencies
 RUN composer install --no-interaction --no-dev --optimize-autoloader

 # Set permissions
 RUN chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache

 # Expose port 9000
 EXPOSE 9000

 # Start PHP-FPM
 CMD ["php-fpm"]
