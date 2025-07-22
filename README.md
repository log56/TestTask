To run project do following:

1. create .env for configuration:
   cp .env.example .env
2. generate keys:
   php artisan key:generate
   php artisan jwt:secret
3. configure .env:
    DB_CONNECTION=pgsql //or other db
    DB_HOST=127.0.0.1 
    DB_PORT=5432
    DB_DATABASE=test // or any other
    DB_USERNAME=user // your username and password
    DB_PASSWORD=password
   
4. install dependancies:
   cd TestTask
   composer install
5. run migrations:
   php artisan migrate
6. run server
   php artisan serve
7. download and import postman collection 
   [download](.TestTask.postman_collection.json)
    
