# Project 27 - Node.js Library Management System

## Description
A Node.js library management system where librarians can add books and view all books. Uses MySQL for storage and provides a REST API with browser UI.

## Tech Stack
- Node.js, Express.js
- MySQL (XAMPP)

## Prerequisites
- Node.js installed
- XAMPP MySQL running

## Dependencies (auto-installed via npm install)
- express
- mysql
- body-parser
- cors

## Setup & Run

### Step 1 - Install all dependencies
```bash
cd project27-nodejs-library
npm install
```
This automatically installs express, mysql, cors and all packages from package.json.

### Step 2 - Start XAMPP MySQL
Open XAMPP Control Panel and start MySQL.

### Step 3 - Start the server
```bash
node app.js
```

## URL
http://localhost:4000

## API Endpoints
| Method | URL | Description |
|--------|-----|-------------|
| GET | /api/books | Get all books |
| GET | /api/books/:id | Get book by ID |
| POST | /api/books | Add new book |
| DELETE | /api/books/:id | Delete book |

## Database
- Database: `library_db`
- Table: `books` (book_id, title, author, year, genre, created_at)
- Auto-created on first run
- View in phpMyAdmin: http://localhost:8081/phpmyadmin

## Features
- Add books with title, author, year, genre
- View all books in a table
- Delete books
- Sample books pre-loaded on first run
