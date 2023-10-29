# Mini Admin Panel - Backend (Laravel)

The Mini Admin Panel backend is built using Laravel, providing the necessary API endpoints to support the frontend application. This README will guide you through the setup and usage of the backend.

## Features

The backend offers the following features:

- API endpoints for user authentication.
- API endpoints for managing companies (CRUD operations).
- API endpoints for managing employees (CRUD operations).
- Integration with the Mini Admin Panel frontend.

## Prerequisites

Before you begin, make sure you have the following software installed:

- PHP
- Composer
- Laravel
- MySQL or your preferred database system

## Installation and Setup

1. Clone the repository: `git clone https://github.com/AsadSyed4/mini-admin-panel-backend.git`.
2. Navigate to the project directory: `cd mini-admin-panel-backend`.
3. Install PHP dependencies: `composer install`.
4. Create a `.env` file and configure your database settings.
5. Generate a new application key: `php artisan key:generate`.
6. Run database migrations: `php artisan migrate`.
6. Run database seeders for dummy: `php artisan db:seed`.
7. Start the Laravel development server: `php artisan serve`.

The backend should now be up and running on `http://localhost:8000`.

## API Endpoints

### Authentication

- `POST /api/login`: Log in as an admin and receive an authentication token.

### Companies

- `GET /api/companies`: Retrieve a list of companies.
- `GET /api/companies/{id}`: Retrieve a specific company by ID.
- `POST /api/companies`: Create a new company.
- `PUT /api/companies/{id}`: Update an existing company by ID.
- `DELETE /api/companies/{id}`: Delete a company by ID.

### Employees

- `GET /api/employees`: Retrieve a list of employees.
- `GET /api/employees/{id}`: Retrieve a specific employee by ID.
- `POST /api/employees`: Create a new employee.
- `PUT /api/employees/{id}`: Update an existing employee by ID.
- `DELETE /api/employees/{id}`: Delete an employee by ID.

## Credentials
Credentials for admin
- Email: admin@admin.com
- Password: password

## Testing

The project includes unit tests for API endpoints. You can run them using:

- php artisan test

## Dependencies
The Laravel backend relies on several external dependencies, which are managed via Composer. Key dependencies include:

- **Laravel Sanctum:** JavaScript framework for building the application's user interface.
- MySQL or your preferred database system.

# Conclusion
The Mini Admin Panel backend provides a robust API to support the frontend application. With this backend in place, you can create, read, update, and delete companies and employees seamlessly. Explore the codebase, run the development server, and enhance the functionality as needed for your admin panel.
