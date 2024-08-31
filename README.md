# Symfony Docker Application Setup Instructions

## Setup and Installation

1. Build the Docker Images
   docker-compose build --no-cache

2. Start the Application
   docker-compose up -d

3. Install PHP Dependencies
   docker-compose exec app composer install

4. Run Database Migrations
   docker-compose exec app php bin/console doctrine:migrations:migrate

5. Create an Admin User
   docker-compose exec app php bin/console app:create-admin-user admin@contact.com admin

## Stopping and Cleaning Up

1. Stop the Containers
   docker-compose stop

2. Bring Down the Containers
   docker-compose down

3. Bring Down and Remove Volumes
   docker-compose down --volumes

## Additional Notes

- Ensure Docker and Docker Compose are installed on your system.
- Adjust environment variables in `.env` and configuration settings in `docker-compose.yml` as needed.
- For development, you might prefer running `docker-compose up` without `-d` to view logs in the terminal.
- This application is configured for development purposes. Additional steps and configurations are required to prepare it for a production environment.
