# **Task Manager - Backend**

## Overview

This is the backend service for Task Manager, a full-stack task management web application. It is built using Laravel with PostgreSQL as the database. The backend handles user management, task management, and sharing functionality. The application uses Sanctum for secure authentication.

## Features

1. **User Management**
    - User registration and login
    - User profiles with name, email, and username
2. **Task Management**
    - Create, read, update, and delete task lists
    - Add, edit, remove, and mark tasks as complete/incomplete
3. **Sharing Functionality**
    - Share task lists with permissions (`View only` or `Edit`)
    - Access lists shared with you

## Tech Stack

-   **Framework:** Laravel
-   **Database:** PostgreSQL
-   **Authentication:** Sanctum
-   **Containerization:** Docker

---

## Setup Instructions

### Prerequisites

Ensure the following are installed:

-   **Docker** and **Docker Compose**
-   **Composer** for dependency management

### Steps to Set Up

1. Clone the repository:
    ```bash
    git clone <repository-url>
    cd backend
    ```
2. Copy the `.env.example` to `.env`:
    ```bash
    cp .env.example .env
    ```
3. Update the `.env` file with your database configuration:
    ```plaintext
    DB_CONNECTION=pgsql
    DB_HOST=db
    DB_PORT=5432
    DB_DATABASE=taskify
    DB_USERNAME=your_username
    DB_PASSWORD=your_password
    ```
4. Start Docker containers:
    ```bash
    docker-compose up --build
    ```
5. Run migrations and seeders:
    ```bash
    docker exec -it app php artisan migrate --seed
    ```
6. Generate the application key:
    ```bash
    docker exec -it app php artisan key:generate
    ```

### API Documentation

Below is a basic list of endpoints:

#### Authentication

-   **POST** `/api/register` - Register a new user
-   **POST** `/api/login` - Log in
-   **POST** `/api/logout` - Log out

#### User Profile

-   **GET** `/api/user` - Get logged-in user details
-   **PUT** `/api/user` - Update user profile

#### Task Lists

-   **GET** `/api/task-lists` - List all task lists
-   **POST** `/api/task-lists` - Create a new task list
-   **PUT** `/api/task-lists/{id}` - Update a task list
-   **DELETE** `/api/task-lists/{id}` - Delete a task list

#### Tasks

-   **GET** `/api/task-lists/{list_id}/tasks` - List tasks in a specific list
-   **POST** `/api/task-lists/{list_id}/tasks` - Add a task to a list
-   **PUT** `/api/tasks/{id}` - Update a task
-   **DELETE** `/api/tasks/{id}` - Remove a task

#### Sharing

-   **POST** `/api/task-lists/{id}/share` - Share a task list
-   **GET** `/api/shared` - View task lists shared with you

### Tests

---
