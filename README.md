# Laravel User Management

A simple User Management system built with **Laravel 12**, **MySQL**, **Laravel Sail**, and **Docker Compose**.

## Overview
This project is a Laravel version of the same user/role management flow from your PHP project.

Includes:
- Authentication (login/logout) with session
- Role & Permission management (RBAC)
- Users CRUD basics (list/create/edit, toggle active)
- UI authorization (hide buttons/links based on permissions)

## Getting Started
1. Copy environment file:
```bash
cp .env.example .env
```

2. Install PHP dependencies (required before Sail can run):
```bash
composer install
```

3. Start Sail containers:
```bash
./vendor/bin/sail up -d --build
```

4. Generate app key and run migrations + seeders:
```bash
./vendor/bin/sail artisan key:generate
./vendor/bin/sail artisan migrate:fresh --seed
```

5. Open application:
- App: [http://localhost](http://localhost)
- Login: [http://localhost/login](http://localhost/login)
- Health endpoint: [http://localhost/health](http://localhost/health)
- Permissions debug: [http://localhost/me/permissions](http://localhost/me/permissions)

## Database Setup
This project uses Laravel migrations and seeders (no raw SQL scripts needed).

Run full reset + seed:

```bash
./vendor/bin/sail artisan migrate:fresh --seed
```

Seed only:

```bash
./vendor/bin/sail artisan db:seed
```

## Default Login
- Username: `admin`
- Password: `admin123`

## Useful Commands
Clear Laravel caches:

```bash
./vendor/bin/sail artisan optimize:clear
```

Stop containers:

```bash
./vendor/bin/sail down
```
