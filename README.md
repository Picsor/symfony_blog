# SETUP
This project uses MySQL database, so you need to have it installed on your machine.

## Principle

- `composer install` to retrieve all packages

- add `DATABASE_URL=<your_database>` on .env file

- (optional) create database with `php bin/console doctrine:database:create`

- add entities to database with `php bin/console doctrine:migrations:migrate`

## User creation and manipulation

- A administrator can be created by going at the following URL: `http://127.0.0.1:8000/register/username/password`. You can copy paste the following in your browser to create a working user: `http://127.0.0.1:8000/register/ABCD/Qsmhio!!!112312`. Please go to the [login page](http://127.0.0.1:8000/login) to login with the created user. Login in with an admin user will send you to the dashboard where you can manage your Menus.
- A standard user can be created by going at the following URL: `http://127.0.0.1:8000/register`. You will be redirected to the [login page](http://127.0.0.1:8000/login) after the creation.
- In the [home page](http://127.0.0.1:8000/), The `See Dishes` will allow you to see the dishes of the restaurant. The `Make A Reservation` will allow you to make a reservation. The `See My Reservations` will allow you to see the reservations you made.