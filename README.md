# Laravel Application


## [localhost postman doc](https://documenter.getpostman.com/view/14032725/2sAYQWJCt2#4a29f340-a2b9-48a7-8b20-0068bf4925e5)


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

### 8. Build Docker

```
docker-compose up --build

```

### 9. Serve docker on http://localhost:8080

```
http://localhost:8080

```