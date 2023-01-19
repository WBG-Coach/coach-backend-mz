
# Coach Digital - Backend

This is the BACKEND of the "Aprender+" project, created by the World Bank to help evaluate teachers and manage improvements in teaching through feedback.


## DOCs

See the [Wiki Documentation](https://github.com/WBG-Coach/coach-backend/wiki) to understand all services and End-Points.


## Technologies Used

**PHP** (version: 8.0)

**Laravel Framework** (version: ^8.75)

**MySQL** (version: 5.7.34)
## Authors

- [https://www.github.com/jmoreirafilho](https://www.github.com/jmoreirafilho)

## Deploy

To deploy this application you just need a linux server with PHP, Database (mysql in this example) and Composer installed and configured. 
If you preferee, its possible to execute this application by Docker image.

### Steps to run using configured server
*** Assuming the server is already configured correctly ***
```bash
  1. git clone https://github.com/WBG-Coach/coach-backend.git
  2. cd coach-backend/
  3. cp .env.example .env
  3.1. set DB_HOST to 'mysql' into .env file
  3.2. set REDIS_HOST to 'mysql' into .env file
  3.3. set DB_DATABASE to 'coach' into .env file
  3.4. set DB_USERNAME to 'coach' into .env file
  3.5. set DB_PASSWORD to 'coach_pass' into .env file
  4. composer install
  5. php artisan key:generate
  6. configure Database
  6.1. create database called 'coach'
  6.2. create user called 'coach'
  6.3. set password for user 'coach' to 'coach_pass'
  7. php artisan migrate
```


### Steps to run using a docker image
*** Assuming Docker is correctly instaled in your Server ***
```bash
  1. git clone https://github.com/WBG-Coach/coach-backend.git
  2. cd coach-backend/
  3. cp .env.example .env
  3.1. set DB_HOST to 'mysql' into .env file
  3.2. set REDIS_HOST to 'mysql' into .env file
  3.3. set DB_DATABASE to 'coach' into .env file
  3.4. set DB_USERNAME to 'coach' into .env file
  3.5. set DB_PASSWORD to 'coach_pass' into .env file
  4. git clone https://github.com/Laradock/laradock.git
  5. cd laradock/
  6. cp .env.example .env
  6.1. set COMPOSE_PROJECT_NAME to 'coach' into .env file
  6.2. set PHP_VERSION to '7.4' into .env file
  6.3. set MYSQL_DATABASE to 'coach' into .env file
  6.4. set MYSQL_USER to 'coach' into .env file
  6.5. set MYSQL_PASSWORD to 'coach_pass' into .env file
  7. enter into WORKSPACE directory
  7.1. composer install
  7.2. php artisan key:generate
  7.3. php artisan migrate
```

