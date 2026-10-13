#!/bin/bash
set -e

# Support dynamic PORT environment variable (Render, Cloud Run, Koyeb, etc.)
if [ -n "$PORT" ] && [ "$PORT" != "80" ]; then
    echo "Configuring Apache to listen on port $PORT..."
    sed -i "s/Listen 80/Listen $PORT/g" /etc/apache2/ports.conf
    sed -i "s/<VirtualHost \*:80>/<VirtualHost \*:$PORT>/g" /etc/apache2/sites-available/*.conf
fi

# Ensure database config exists (falls back to example template which uses getenv)
if [ ! -f /var/www/html/config/database.php ]; then
    cp /var/www/html/config/database.php.example /var/www/html/config/database.php
fi

# If DB_SSL_CA_CONTENT is provided in env, save it to certs directory
if [ -n "$DB_SSL_CA_CONTENT" ]; then
    mkdir -p /etc/ssl/certs
    echo "$DB_SSL_CA_CONTENT" > /etc/ssl/certs/cloud_db_ca.pem
    export DB_SSL_CA="/etc/ssl/certs/cloud_db_ca.pem"
fi

# Ensure upload directories exist and are owned by Apache user
mkdir -p /var/www/html/public/uploads/clinical_photos
mkdir -p /var/www/html/public/assets/uploads/profiles
chown -R www-data:www-data /var/www/html/public/uploads /var/www/html/public/assets/uploads
chmod -R 775 /var/www/html/public/uploads /var/www/html/public/assets/uploads

exec "$@"
