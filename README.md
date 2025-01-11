# Laravel Application

This is a Laravel-based web application with a fully documented API, generated using [Scribe](http://127.0.0.1:8000/docs). on http://127.0.0.1:8000/

## [staging app url](http://34.244.254.103/)

## [staging postman doc](https://documenter.getpostman.com/view/14032725/2sAYQWJYk8)

## [staging scribe doc](http://34.244.254.103/docs)




## Features

- RESTful API endpoints
- User registration and authentication
- Task management functionality
- Rate-limiting middleware for security
- Comprehensive unit and feature tests
- Laravel Scribe-generated API documentation


---

## Requirements

Ensure the following are installed on your system:

- PHP 8.2 or higher
- Composer
- MySQL

---

## Installation

Follow these steps to set up the application:

### 1. Clone the Repository
<!-- ```bash -->
```
git clone git@github.com:hummingbed/testing-lara.git
```
### 2. cd into the app

```
cd testing-lara
```

### 3. Install Dependencies
```
composer install

```

### 4. copy .env.example to .env

### 5. Generate Application Key

```
php artisan key:generate
```
### 6. Run Migrations

```
php artisan migrate
```

### 7. Running Tests

```
php artisan test

```