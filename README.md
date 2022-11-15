# Install

docker build -t my-php-app .\
docker run -dit -v $(pwd):/var/www/myapp --name my-running-app my-php-ap\
docker exec -it my-running-app composer install

# run tests
docker exec -it my-running-app ./vendor/bin/codecept run