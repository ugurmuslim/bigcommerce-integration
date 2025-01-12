# Bigcommerce Integration 

This project integrates with Bigcommerce store with access tokens and syncs categories, products and variants.

## Tech Stack

- Vue 3
- PHP 8
- Laravel 11
- Docker
- Mysql

## About The Project

Shops are stored in database with access tokens. 

There are two jobs which run s every 10 minutes to sync categories, products and variants from all stores in database.

When a shop logs in with correct credentials, they are redirected to Categories page where they can navigate to Products and Variants. When the project starts it will run one time to gather all the data from all the stores in database. 

Because Bigcommerce can heave multiple categories for products I used hasMany relationship between Category and Product.

## Installation

1. Run `docker-compose up --build ` to start the containers.

```sh
docker-compose up --build
```

2. Run `docker-compose exec app php artisan app:project-init` to make the project ready.

```sh
docker-compose exec app php artisan app:project-init
```


