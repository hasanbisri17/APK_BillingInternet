#!/bin/bash

# Log function
log() {
    echo "[$(date '+%Y-%m-%d %H:%M:%S')] $1"
}

# Change to the project directory
cd "$(/var/www/APK_BillingInternet "$0")"

log "Starting deployment..."

# Pull latest changes from GitHub
log "Pulling latest changes from GitHub..."
git pull origin main

# Install/update PHP dependencies
log "Installing/updating PHP dependencies..."
composer install --no-interaction --prefer-dist --optimize-autoloader

# Install/update NPM dependencies and build assets
log "Installing/updating NPM dependencies..."
npm install
log "Building assets..."
npm run build

# Clear all Laravel caches
log "Clearing Laravel caches..."
php artisan cache:clear
php artisan config:clear
php artisan route:clear
php artisan view:clear

# Run database migrations without losing data
log "Running database migrations..."
php artisan migrate --force

# Optimize Laravel
log "Optimizing Laravel..."
php artisan optimize

# Set proper permissions
log "Setting proper permissions..."
chmod -R 775 storage bootstrap/cache
chown -R www-data:www-data storage bootstrap/cache

log "Deployment completed successfully!"
