# Project 14 - Spring Boot Online Bookstore

## Question
Design and develop a responsive website for an online book store using Spring Boot and MySQL having:
1. Home Page
2. Login Page
3. Catalog Page
4. Registration Page (database)

## Tech Stack
- Java 17, Spring Boot 3.0
- Spring Data JPA + MySQL (XAMPP)
- Spring Security (Form Login)
- Thymeleaf (server-side templates)
- Maven

## Prerequisites
- Java JDK 17+ installed
- Maven installed
- XAMPP MySQL running

## Setup & Run

### Step 1 - Start XAMPP MySQL
Open XAMPP Control Panel → Start MySQL

### Step 2 - Run the application
```bash
cd project14-spring-bookstore
mvn clean spring-boot:run
```
Database `bookstore_db` and tables are auto-created on first run.
8 sample books are loaded automatically.

## URL
http://localhost:8087

## Pages
| Page | URL |
|------|-----|
| Home | http://localhost:8087/home |
| Login | http://localhost:8087/login |
| Register | http://localhost:8087/register |
| Catalog | http://localhost:8087/catalog |

## Database
- Database: `bookstore_db` (auto-created)
- Tables: `books`, `users`
- View in phpMyAdmin: http://localhost:8081/phpmyadmin

## REST API Endpoints (require Basic Auth: admin/admin123)
| Method | URL | Description |
|--------|-----|-------------|
| GET | /api/books | Get all books |
| GET | /api/books/{id} | Get book by ID |
| GET | /api/books/category/{cat} | Get by category |
| GET | /api/books/search/{title} | Search by title |
| POST | /api/books | Create book |
| PUT | /api/books/{id} | Update book |
| DELETE | /api/books/{id} | Delete book |
