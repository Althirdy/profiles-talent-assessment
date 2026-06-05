# Profiles Employee Management System

Profiles is a Laravel-based assessment project for a secure login, registration, and employee records management system. Authenticated users can manage employee directory records from a protected dashboard.

## Features

- User registration
- User login and logout using sessions
- Protected dashboard for authenticated users
- Role-based access control for admin and employee users
- Employee CRUD: create, read, update, and delete records
- Server-side form validation
- Bootstrap 5 dashboard and forms
- CSRF-protected POST, PUT, and DELETE forms
- PDO prepared statements for authentication and employee CRUD queries

## Tech Stack

- PHP 8.3+
- Laravel 13
- MySQL 8.4
- Bootstrap 5
- Laravel Sail

## Setup

Copy the environment file:

```bash
cp .env.example .env
```

Start the Sail containers:

```bash
./vendor/bin/sail up -d
```

Generate the application key:

```bash
./vendor/bin/sail artisan key:generate
```

Run fresh migrations and seed the database:

```bash
./vendor/bin/sail artisan migrate:fresh --seed
```

Open the application:

```text
http://localhost
```

## Default Admin Account

```text
Email: admin@profiles.com
Password: passwordAdmin123
```

## Default Employee Account

```text
Email: juan@profiles.com
Password: passwordUser123
```

## Role-Based Access Control

The system uses a simple `role` field on the `users` table.

- `admin`: can view the dashboard and create, update, or delete employee records.
- `employee`: can log in and view employee records, but cannot create, update, or delete them.

New registered users use the default non-admin role defined by the database migration.

## Database Structure

The database schema is defined through Laravel migrations.

- `users`: stores registered user accounts, hashed passwords, and roles.
- `employee_records`: stores employee name, position, and unique email records.

Seed data is provided through `DatabaseSeeder`, including default admin and employee accounts plus sample employee records.

## Security Notes

- Passwords are hashed with `password_hash()`.
- Login credentials are verified with `password_verify()`.
- Authentication and employee CRUD queries use PDO prepared statements.
- Forms are protected with Laravel CSRF tokens.
- Dashboard and employee CRUD actions require an active login session.
- Employee CRUD actions are restricted to admin users.
