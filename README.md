# Transaction App

A Laravel-based money transfer application with transaction management.

## Features
- Transfer money between accounts
- Transaction history
- Form validation with error handling
- Database locking to prevent race conditions

## Tech Stack
- Laravel
- MySQL
- Docker (Laravel Sail)
- Blade templating

## Setup
1. Clone the repository
2. Run `composer install`
3. Copy `.env.example` to `.env`
4. Run `./vendor/bin/sail up -d`
5. Run `./vendor/bin/sail artisan migrate:fresh --seed`
