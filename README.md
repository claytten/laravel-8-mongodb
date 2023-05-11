Laravel 8 Restful API + MongoDB
===============
This repo is functionality complete — PRs and issues welcome!

## Requirement
 * `PHP 8.0>`
 * `NGINX/Apache (if you run on localhost, just use php artisan serve on console)`
 * `Mongodb Server 4.2`
 * `composer`
 * `Mongodb Driver for PHP` [link](http://php.net/manual/en/mongodb.installation.php)

## Installation
Clone the repository
```
git clone https://github.com/claytten/laravel-8-mongodb.git
```
Switch to the repo folder
```
cd laravel-8-mongodb
```
Install all the dependencies using composer
```
composer install
```
Copy the example env file and make the required configuration changes in the .env file
```
cp .env.example .env
```
Generate a new application key
```
php artisan key:generate
```
Run the database migrations with seeder (**Set the database connection in .env before migrating**)
```
php artisan migrate:fresh --seed
```
Start the local development server
```
php artisan serve
```
You can now access the server at http://localhost:8000

Testing
------------
Make sure you are already setup but not start deploy server
Run this code to testing
```
php artisan test
```

## Dependencies
 * [jenssegers/mongodb](https://github.com/jenssegers/laravel-mongodb) `V3.8`
 * [laravel/sanctum](https://github.com/spatie/laravel-permission) `V2.15`

## Environment variables

- `.env` - Environment variables can be set in this file

***Note*** : You can quickly set the database information and other variables in this file and have the application fully working.

## License
Laravel 8 Restful API + MongoDB is open-sourced software licensed under the [MIT license].