# Install

docker build -t my-php-app .\
docker run -dit -v $(pwd):/var/www/myapp --name my-running-app my-php-app\
docker exec -it my-running-app composer install

# run tests
docker exec -it my-running-app ./vendor/bin/codecept run

# why yii2? 
because I worked with this framework and I know how dependency injection works in this framework for further development