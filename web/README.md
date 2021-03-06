
## Installed dependencies
`composer require php-amqplib/php-amqplib`
`composer require predis/predis`

## Run the app and services
- Run Docker
`docker compose up`
- Inside http docker
`docker exec -it housfy-http bash`
- Run php queue consumer
`php consumer.php`

## Check logs (it only logs in local environment)
- Inside http Docker
`docker exec -it housfy-http bash`
- Tail the logs (first time file doesn't exists, only after run consuer and call the api get/id method: i.e. http://localhost:8080/api/offices/8)
`tail -f /tmp/log`

## Tests
- Inside http Docker
`docker exec -it housfy-http bash`
`cd /var/www/html/web`
- Run tests
`php artistan test`

## Suggestions / improvements
- Implement events subscriptions for the consumer with Nodejs and PM2 or a command with cron to ensure it works always any other way instead of a simple PHP script
- Check if redis and rabbit are available, if not, just not use them (to be able to run easily php artisan serve)

### Running the app from zero
- Download the Repo
`git clone https://github.com/oferraro/housfy.git`
- Enter in Docker folder
`cd housfy/docker`
- Copy config file conf/env_example to conf/.env
`cp conf/env_example conf/.env`
- Run Docker
`docker-compose up`
- Enter in Docker/http for init consumer (Rabbit queue)
`docker exec -it housfy-http bash`
`cd web`
- Install dependencies 
`composer install`
- Copy .env of Laravel project
`cp .env.example .env`
- Delete (if exists) /tmp/log
`rm /tmp/log`
- Run the queue consumer
`php consumer.php`

