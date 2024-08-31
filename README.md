# Symfony Docker Application Setup Instructions

## Setup and Installation

1. Build the Docker Images
   `docker-compose build --no-cache`

3. Start the Application
   `docker-compose up -d`

4. Install PHP Dependencies
   vdocker-compose exec app composer install`

5. Run Database Migrations
   `docker-compose exec app php bin/console doctrine:migrations:migrate`

6. Create an Admin User
   `docker-compose exec app php bin/console app:create-admin-user admin@contact.com admin`

## Stopping and Cleaning Up

1. Stop the Containers
   `docker-compose stop`

2. Bring Down the Containers
   `docker-compose down`

3. Bring Down and Remove Volumes
   `docker-compose down --volumes`

## Additional Notes

- Ensure Docker and Docker Compose are installed on your system.
- Adjust environment variables in `.env` and configuration settings in `docker-compose.yml` as needed.
- For development, you might prefer running `docker-compose up` without `-d` to view logs in the terminal.
- You can test the app by loging in as an admin using email: admin@contact.com and password "admin".
- This application is configured for development purposes. Additional steps and configurations are required to prepare it for a production environment.
