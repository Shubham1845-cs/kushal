# Project 14: Spring Boot Book Store

## Overview
A Spring Boot application for browsing and managing books with MySQL database and Spring Security authentication.

## Technologies Used
- Spring Boot 3.0.0
- Spring Security (Basic Authentication)
- Spring Data JPA
- MySQL Database
- Lombok

## Prerequisites
- Java 17+
- Maven
- MySQL running on localhost:3306
- Username: root, Password: (empty)

## Installation & Setup

### Step 1: Install Dependencies
```bash
cd project14-spring-bookstore
mvn clean install
```

**Maven Dependencies Installed:**
1. **spring-boot-starter-web**: Web server, REST controller support, JSON handling
2. **spring-boot-starter-data-jpa**: ORM for MySQL database operations
3. **mysql-connector-java 8.0.32**: JDBC driver for MySQL connectivity
4. **spring-boot-starter-security**: Authentication and authorization framework
5. **lombok**: Reduces boilerplate code with @Data, @Getter, @Setter annotations
6. **spring-boot-devtools**: Auto-restart on file changes

### Step 2: Run Application
```bash
mvn spring-boot:run
```

Server runs on **http://localhost:8080**

## Database Setup
Application automatically creates `bookstore_db` database with:
- `books` table: id, title, author, price, description, quantity, category
- `users` table: id, username, email, password, role

## Features
- Browse all books in catalog
- Search books by title or category
- Add, update, and delete books (admin only)
- Secure endpoints with Basic Authentication
- Role-based access control (ADMIN/USER)

## Authentication
**Test Credentials:**
- Admin: admin / admin123 (Can manage books)
- User: user / user123 (Can view books only)

All API requests require Basic Auth header:
```
Authorization: Basic base64(admin:admin123)
```

## REST API Endpoints

### Get All Books
```bash
GET http://localhost:8080/api/books
Authorization: Basic admin:admin123
```

### Get Book by ID
```bash
GET http://localhost:8080/api/books/1
Authorization: Basic admin:admin123
```

### Search Books
```bash
GET http://localhost:8080/api/books/search/fiction
Authorization: Basic admin:admin123
```

### Get Books by Category
```bash
GET http://localhost:8080/api/books/category/fiction
Authorization: Basic admin:admin123
```

### Create New Book (POST)
```bash
POST http://localhost:8080/api/books
Authorization: Basic admin:admin123
Content-Type: application/json

{
  "title": "Java Programming",
  "author": "John Doe",
  "price": 499.99,
  "quantity": 50,
  "category": "Programming",
  "description": "Complete Java guide"
}
```

### Update Book (PUT)
```bash
PUT http://localhost:8080/api/books/1
Authorization: Basic admin:admin123
Content-Type: application/json

{
  "title": "Updated Title",
  "author": "New Author",
  "price": 599.99,
  "quantity": 45,
  "category": "Programming",
  "description": "Updated description"
}
```

### Delete Book
```bash
DELETE http://localhost:8080/api/books/1
Authorization: Basic admin:admin123
```

## Project Structure
```
project14-spring-bookstore/
├── src/main/java/com/example/bookstore/
│   ├── BookstoreApplication.java
│   ├── model/
│   │   ├── Book.java
│   │   └── User.java
│   ├── repository/
│   │   ├── BookRepository.java
│   │   └── UserRepository.java
│   ├── controller/
│   │   └── BookController.java
│   └── config/
│       └── SecurityConfig.java
├── src/main/resources/
│   ├── templates/
│   │   └── index.html
│   └── application.properties
├── pom.xml
└── README.md
```

## Testing
1. Open **http://localhost:8080** in browser
2. Login with admin/admin123
3. Browse books in catalog
4. Add, edit, or delete books

## Troubleshooting

### MySQL Connection Error
Ensure MySQL is running and database credentials are correct in application.properties

### Port Already in Use
Change `server.port` in application.properties to 8081 or another available port

### Build Failures
```bash
mvn clean install -U
```

## Status
✅ **Project 14 Completed**
- All Java files created and ready
- Maven dependencies configured
- MySQL ORM setup complete
- REST API fully functional
- Web UI created with JavaScript fetch
