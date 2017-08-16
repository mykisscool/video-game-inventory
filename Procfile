web: vendor/bin/heroku-php-apache2
restore: php vendor/bin/phinx seed:run -s VideoGameSeeder && cd ./src/img && find . -maxdepth 1 -type d -not -name 'demo' -not -name ".*" -print0 -exec rm -r {} \; && cp -r ./demo/* .
