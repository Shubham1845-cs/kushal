# Project 13 - PHP Login System

## Description
A PHP user authentication system with registration, login, dashboard, and logout.

## Tech Stack
- PHP, MySQL
- XAMPP Apache + MySQL

## Prerequisites
- XAMPP Apache running on port 8081
- XAMPP MySQL running

## Setup
Visit init_db.php first:
http://localhost:8081/project13-php-login/init_db.php

## URLs
- Register: http://localhost:8081/project13-php-login/register.php
- Login: http://localhost:8081/project13-php-login/login.php
- Dashboard: http://localhost:8081/project13-php-login/dashboard.php
- Logout: http://localhost:8081/project13-php-login/logout.php

## Database
- Database: `login_db`
- Table: `users` (id, username, email, password, created_at)
- Passwords stored as bcrypt hash

## Features
- User registration with email and password
- Secure login with password hashing
- Session-based authentication
- Protected dashboard page
