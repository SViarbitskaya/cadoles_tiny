# symfony basic user management app

docker-compose up -- build

docker-compose exec app php bin/console doctrine:migrations:migrate 
docker-compose exec app php bin/console app:create-admin-user admin@contact.com admin

docker-compose down
docker-compose stop 
docker-compose down --volumes

