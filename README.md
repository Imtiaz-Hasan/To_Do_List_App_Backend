# To-Do List Application - Backend

This is the backend API for the To-Do List application built with Laravel. It provides RESTful endpoints for task management and user authentication.

## Prerequisites

- PHP 8.x
- Composer
- MySQL/MariaDB
- Laravel CLI

## Installation

1. Clone the repository and navigate to the backend directory

2. Install PHP dependencies:
   ```bash
   composer install
   ```

3. Copy the environment file:
   ```bash
   cp .env.example .env
   ```

4. Generate application key:
   ```bash
   php artisan key:generate
   ```

5. Run database migrations:
   ```bash
   php artisan migrate
   ```

6. Create a symbolic link for storage:
   ```bash
   php artisan storage:link
   ```

7. Start the development server:
   ```bash
   php artisan serve
   ```

The API will be available at `http://localhost:8000`

## API Endpoints

### Authentication

- `POST /api/register` - Register a new user
- `POST /api/login` - User login
- `POST /api/logout` - User logout (requires authentication)
- `GET /api/profile` - Get user profile (requires authentication)
- `POST /api/upload-profile-picture` - Upload profile picture (requires authentication)

### Tasks

All task endpoints require authentication:

- `GET /api/tasks` - List all tasks
- `POST /api/tasks` - Create a new task
- `GET /api/tasks/{id}` - Get a specific task
- `PUT /api/tasks/{id}` - Update a task
- `DELETE /api/tasks/{id}` - Delete a task
- `PATCH /api/tasks/{id}/complete` - Mark a task as complete

## Database Schema

The application includes the following main tables:

- `users` - Store user information
- `tasks` - Store task information
- `personal_access_tokens` - For API authentication
- `sessions` - For session management

## Security

- API authentication is handled using Laravel Sanctum
- All sensitive routes are protected with authentication middleware
- CORS is configured for frontend access

## Development

- The application follows Laravel's MVC architecture
- API routes are defined in `routes/api.php`
- Controllers are located in `app/Http/Controllers`
- Models are located in `app/Models`

## License

This project is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).
