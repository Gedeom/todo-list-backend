# To do List

### Repository with the purpose of creating a to do list for knowledge testing

#### Stacks

* Framework Laravel version 8.75
* PHP 8.1
* Database MariaDB 10.7.3 (MySQL Compatible)

#### Requeriments
* PHP 8.1+
* Composer 2.3+
* MariaDB 10.7+

#### Database
* Name: db_todolist
* User: root
* Password: FMpfD2SdVm
  
#### Installation**
```sh
cd todo-list-backend
cp .env.example .env
composer install
php artisan key:generate
php artisan migrate
php artisan db:seed
php artisan serve
```

#### Docker
To do list repository is very easy to install and deploy in a Docker container.

```sh
cd todo-list-backend
docker-compose up -d --build
docker-compose exec app bash
composer install
php artisan key:generate
php artisan migrate
php artisan db:seed
```
To access the website, go to the address below.

```sh
127.0.0.1:8888
```

> Note: By default the port is 80:80, however to access the website it was changed to 8888:80, but it can be changed if you choose.
