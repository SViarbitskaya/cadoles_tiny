# Company and User Administration Platform

# Symfony Docker Application Setup Instructions

## Setup and Installation

1. Build the Docker Images
   `docker-compose build`

3. Start the Application
   `docker-compose up -d`

4. Create the admin user, if necessary
   `php bin/console app:create-admin-user admin@contact.com admin`

## Stopping and Cleaning Up

1. Stop the Containers
   `docker-compose stop`create-admin-user

2. Bring Down the Containers
   `docker-compose down`

3. Bring Down and Remove Volumes
   `docker-compose down --volumes`

## Additional Notes

- Ensure Docker and Docker Compose are installed on your system.
- Adjust environment variables in `.env` and configuration settings in `docker-compose.yml` as needed.
- For development, you might prefer running `docker-compose up` without `-d` to view logs in the terminal.
- You can test the app by loging in as an admin using email: admin@contact.com and password "admin".
- This application is configured for development purposes. Additional steps and configurations are required to prepare it for a production environment (adjusting environmental variables, compiling assets with assets mapper, adjusting docker configuration, etc.)

## Features

Symfony 7.1.x

### User Management:

   - Users have a login, password, email, avatar, and belong to specific roles and groups.
   - Users can belong to multiple user groups.

### User Groups:

   - Users are associated with user groups.
   - Only administrators can create and assign users to user groups.

### Roles and Permissions:

   - Administrator: Full permissions, including managing user groups.
   - User: Can view only their groups.

### Authentication & Security:

   - Implemented login and password authentication.
   - Password hashing for secure storage.
   - Role-based access control: users can only access the data they are authorized to view.

### Admin Console:

   - Admin users can manage users and groups via an admin console.
   - Admins can create, modify, and delete users and groups.

### Styling:

   - Uses `importmap` for handling assets
   - Uses Bootstrap for user interface styling.
   - Usese Datatables to handle tabulated data.

### Docker Setup:

   - Application can run inside Docker containers together with PostgreSQL as the database.


