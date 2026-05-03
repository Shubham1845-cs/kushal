# Project 34 - Blog Management REST API

## Description
A Blog Management REST API built with Express.js and MySQL. Supports full CRUD operations with a browser UI and JSON responses.

## Tech Stack
- Node.js, Express.js
- MySQL (XAMPP)

## Prerequisites
- Node.js installed
- XAMPP MySQL running

## Dependencies (auto-installed via npm install)
- express
- mysql

## Setup & Run

### Step 1 - Install all dependencies
```bash
cd project34-blog-api
npm install
```
This automatically installs express, mysql and all packages from package.json.

### Step 2 - Start XAMPP MySQL
Open XAMPP Control Panel and start MySQL.

### Step 3 - Start the server
```bash
node app.js
```

## URL
http://localhost:5000

## API Endpoints
| Method | URL | Body | Description |
|--------|-----|------|-------------|
| GET | /api/blogs | - | Get all blogs |
| GET | /api/blogs/:id | - | Get blog by ID |
| POST | /api/blogs | { title, author, content, category } | Create blog |
| PUT | /api/blogs/:id | { title, author, content, category } | Update blog |
| DELETE | /api/blogs/:id | - | Delete blog |

## Database
- Database: `blog_db`
- Table: `blogs` (id, title, author, content, category, created_at, updated_at)
- Auto-created on first run with sample data
- View in phpMyAdmin: http://localhost:8081/phpmyadmin

## Features
- Full CRUD via browser UI and REST API
- Data stored in MySQL (persists after restart)
- JSON responses for all API calls
- Sample blogs pre-loaded on first run
- Test with Postman or browser
