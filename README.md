<p align="center"><a href="https://laravel.com" target="_blank"><img src="https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logolockup-cmyk-red.svg" width="400" alt="Laravel Logo"></a><img src="./public/img/LaraBuild(transparent)-06.png" width="400" alt="Laravel Logo" style="padding-bottom: 10px;"></p>

<p align="center">
<a href="https://github.com/laravel/framework/actions"><img src="https://github.com/laravel/framework/workflows/tests/badge.svg" alt="Build Status"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/dt/laravel/framework" alt="Total Downloads"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/v/laravel/framework" alt="Latest Stable Version"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/l/laravel/framework" alt="License"></a>
</p>

## About LaraBuild

**LaraBuild** is a Laravel scaffolding system designed to automate the creation of core components such as models, controllers, views, migrations, and API controllers. It provides a user-friendly interface for managing database operations, CRUD generation, and user roles while ensuring adherence to Laravel’s best practices. This tool is ideal for developers looking to speed up project setup and maintain consistent code quality.

## Features

-   **Automated Core Component Generation**  
    Automatically generates models, controllers, views, migrations, and API controllers for CRUD operations.

-   **Database Management Module**  
    Allows developers to manage database tables, define columns, and set up relationships (HasOne, HasMany, BelongsTo) through an interactive interface.

-   **User Role Management**  
    Easily manage user roles via the `DatabaseSeeder` and ensure proper role-based access control.

-   **Customizable Scaffolding**  
    LaraBuild's features can be enabled or disabled by setting the `LARA_BUILD` value in the `.env` file.

## Setup Instructions

Follow these steps to set up the project:

1. **Clone the project**
    ```bash
    git clone https://github.com/zabuzaff/lara-build.git [your-project-name]
    ```
2. **Install dependencies using composer**
    ```bash
    composer install
    ```
3. **Copy .env.example to .env**
    ```bash
    cp .env.example .env
    ```
4. **Adjust database details in .env**
    ```
     DB_CONNECTION=mysql
     DB_HOST=127.0.0.1
     DB_PORT=3306
     DB_DATABASE=
     DB_USERNAME=root
     DB_PASSWORD=
    ```
5. **Generate the application key**
    ```bash
    php artisan key:generate
    ```
6. **Adjust user roles in DatabaseSeeder**
    ```
    $datas = [
         [
             'name' => 'Admin',
             'email' => 'admin@example.com',
             'password' => bcrypt('password'),
             'role' => 'admin'
         ],
         [
             'name' => 'User',
             'email' => 'user@example.com',
             'password' => bcrypt('password'),
             'role' => 'user'
         ],
     ];
    ```
7. **Run migrations and seed the database**
    ```bash
    php artisan migrate:fresh --seed
    ```
8. **Remove .git folder**
    ```bash
    rm -rf .git
    ```
9. **Ready for use!**

## Notes

To disable the LaraBuild features, set the LARA_BUILD value in the .env file to false.

```
LARA_BUILD=false
```

## License

The Laravel framework is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).
