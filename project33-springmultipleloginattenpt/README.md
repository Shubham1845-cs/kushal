# Project 33 - Spring Boot Multiple Login Attempt Protection

## Question
Develop a Spring Boot application that implements security against multiple failed login attempts. The system locks the account after a certain number of failed attempts.

## Tech Stack
- Java 17, Spring Boot 3.2
- Spring Data JPA + MySQL (XAMPP)
- Spring Security (BCrypt + Login Attempt Tracking)
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
cd project33-springmultipleloginattenpt
mvn clean spring-boot:run
```
Database `login_attempt_db` is auto-created on first run.

## URL
http://localhost:8088

## Pages
| Page | URL |
|------|-----|
| Login / Register | http://localhost:8088 |
| Dashboard | http://localhost:8088/dashboard (after login) |

## Database
- Database: `login_attempt_db` (auto-created)
- Table: `user`
- View in phpMyAdmin: http://localhost:8081/phpmyadmin

## Features
- User registration with BCrypt password hashing
- Login attempt tracking
- Account locking after multiple failed attempts
- Dashboard shows all users with encrypted password hashes
- Session-based authentication
