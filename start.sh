#!/bin/bash

echo "Starting Logopedics application..."

# Start containers
docker-compose -f docker-compose.simple.yml up -d

echo "Waiting for services to be ready..."
sleep 10

# Generate application key
echo "Generating application key..."
docker-compose -f docker-compose.simple.yml exec app php artisan key:generate

# Run migrations
echo "Running database migrations..."
docker-compose -f docker-compose.simple.yml exec app php artisan migrate

# Create storage link
echo "Creating storage link..."
docker-compose -f docker-compose.simple.yml exec app php artisan storage:link

echo "Application is ready!"
echo "Access your application at: http://localhost:8000"
echo "Mail testing tool at: http://localhost:8025" 