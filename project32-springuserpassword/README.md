# Project 32 - Spring Boot Secure Password (BCrypt)

## Question
Develop a Spring Boot application that implements secure user authentication with BCrypt password encryption. Users can register and login with encrypted passwords stored in MySQL.

## Tech Stack
- Java 17, Spring Boot 3.2
- Spring Data JPA + MySQL (XAMPP)
- Spring Security (BCrypt)
- Thymeleaf
- Maven

## Prerequisites
- Java JDK 17+ installed
- Maven installed
- XAMPP MySQL running

## Setup & Run

### Step 1 - Start XAMPP MySQL

### Step 2 - Run the application
```bash
cd project32-springuserpassword
mvn clean spring-boot:run
```
Database `secure_password_db` is auto-created on first run.

## URL
http://localhost:8086

## Pages
| Page | URL |
|------|-----|
| Login / Register | http://localhost:8086 |
| Dashboard | http://localhost:8086/dashboard (after login) |

## Database
- Database: `secure_password_db` (auto-created)
- Table: `user`
- View in phpMyAdmin: http://localhost:8081/phpmyadmin

## Features
- User registration with BCrypt password hashing
- Login with Spring Security form authentication
- Dashboard shows all users with encrypted password hashes
- Session-based authentication
