# Project 10 - Node.js Student Registration System

## Description
A Node.js + Express student registration system with MySQL database. Supports full CRUD operations via REST API and a browser UI.

## Tech Stack
- Node.js, Express.js
- MySQL (XAMPP)

## Prerequisites
- Node.js installed
- XAMPP MySQL running (start MySQL in XAMPP Control Panel)

## Dependencies (auto-installed via npm install)
- express
- mysql
- body-parser
- cors
- dotenv

## Setup & Run

### Step 1 - Install all dependencies
```bash
cd project10-nodejs-student
npm install
```
This automatically installs express, mysql, body-parser, cors and all other packages listed in package.json.

### Step 2 - Start XAMPP MySQL
Open XAMPP Control Panel and start MySQL.

### Step 3 - Start the server
```bash
node app.js
```

## URL
http://localhost:3000

## API Endpoints
| Method | URL | Description |
|--------|-----|-------------|
| GET | /api/students | Get all students |
| GET | /api/students/:id | Get student by ID |
| POST | /api/students | Add new student |
| PUT | /api/students/:id | Update student |
| DELETE | /api/students/:id | Delete student |

## Database
- Database: `student_db` (auto-created on first run)
- Table: `students` (id, name, email, course, created_at)
- View in phpMyAdmin: http://localhost:8081/phpmyadmin
