#!/bin/bash
set -e

echo "Waiting for the database to be ready..."
/usr/local/bin/wait-for-it.sh database:5432 --timeout=30 -- echo "Database is ready!"

# Run database migrations
echo "Running database migrations..."
php bin/console doctrine:migrations:migrate --no-interaction

# Create the admin user
echo "Creating admin user..."
php bin/console app:create-admin-user admin@contact.com admin || true

# Execute the container's main process (Apache)
exec "$@"
