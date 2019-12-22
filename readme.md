# TODO Api in Lumen PHP Framework with JWT token

a very compact todo api in written in Lumen framework devleoped by Laravel team. this api involves user registration and login only for now, in future version other auth modules will be integrated.

i use JWT in this api, you can change the token life time from AuthController line# 45 to whatever the lifetime you required, current it is 24 hours.

## functionalites
- register
- login
- user based categories
- user tasks list
- filter tasks based on category or status



## How to Install
follow the instruction and enjoy.

- download the repository
- update .env file with database settings
- composer install
- php artisan migrate
- php localhost -S localhost:8000

navigate your browser to localhost:8000/api/v1 and enjoy


## Official Documentation

Documentation for the framework can be found on the [Lumen website](https://lumen.laravel.com/docs).


## support

I will be happy if you help me to improve this api with your useful thoughts.