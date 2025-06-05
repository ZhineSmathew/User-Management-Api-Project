# Laravel User Management RESTFUL API Project

This Laravel application provides a REST API for managing users and retrieving user profile details, including multiple address records.  No authentication is required for this task as per the specification.

## Requirements

- PHP >= 8.1
- Composer
- Laravel 12
- Database - Mysql 

## Setup 

1. **Clone the repository**

   git clone https://github.com/your-repo/laravel-user-profile.git
   cd laravel-user-profile
2, **Environment setup**
    composer install
    cp .env.example .env
    php artisan key:generate


3, **DB credentials**
    DB_DATABASE=user_management_api

4, **Migratio**
    php artisan migrate --seed // migrate with user seeder data 

