# Employee WorkTrack

A Laravel-based employee management and work tracking application designed to help teams manage tasks, submit daily updates, and track employee performance reviews.

## Overview

Employee WorkTrack is built for organizations that need a simple internal platform for:

- assigning and tracking employee tasks
- recording daily work updates
- reviewing employee performance
- maintaining task status history
- separating manager and employee access

## Features

### Manager features
- manage employee tasks
- create and assign tasks
- view task status updates
- evaluate performance reviews
- monitor daily work updates

### Employee features
- view assigned tasks
- update task status
- submit daily work summaries
- view performance reviews
- access personal dashboard

## Tech stack

- PHP 8.2
- Laravel 12
- SQLite database
- Blade templates
- Vite frontend assets

## Project structure

- `app/` - application logic, controllers, models, providers
- `database/` - migrations and seeders
- `resources/views/` - Blade templates
- `routes/web.php` - application routes
- `tests/` - test suite

## Requirements

Before running the project, make sure the following are installed:

- PHP 8.2 or newer
- Composer
- Node.js and npm

## Installation

1. Clone the repository
2. Install PHP dependencies:

```bash
composer install
```

3. Create the environment file:

```bash
cp .env.example .env
```

4. Generate the application key:

```bash
php artisan key:generate
```

5. Create the SQLite database file if needed:

```bash
touch database/database.sqlite
```

6. Run database migrations:

```bash
php artisan migrate
```

7. Install frontend dependencies:

```bash
npm install
```

8. Build frontend assets:

```bash
npm run build
```

## Run the application

```bash
php artisan serve
```

Then open the local URL shown in the terminal, usually:

```text
http://127.0.0.1:8000
```

## Default access

The project supports role-based access for:

- employees
- managers

Users can register and log in through the web interface, and the dashboard redirects based on user role.

## License

This project is intended for internal organizational use and follows the Laravel project setup conventions.
