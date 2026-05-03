# Project 35 - Task Manager REST API

## Description
A Task Manager REST API built with Express.js. Manages daily tasks with status tracking (pending/completed).

## Tech Stack
- Node.js, Express.js
- In-memory storage (data resets on restart)

## Prerequisites
- Node.js installed

## Dependencies (auto-installed via npm install)
- express

## Setup & Run

### Step 1 - Install all dependencies
```bash
cd project35-task-manager-api
npm install
```
This automatically installs express and all packages from package.json.

### Step 2 - Start the server
```bash
node app.js
```

## Postman Testing Note
When sending POST, PUT, PATCH requests in Postman:
- Select **Body** tab
- Choose **raw**
- Set dropdown to **JSON** (not Text)
- Then enter your JSON body

## URL
http://localhost:5001

## API Endpoints
| Method | URL | Body | Description |
|--------|-----|------|-------------|
| GET | /api/tasks | - | Get all tasks |
| GET | /api/tasks?status=pending | - | Get pending tasks |
| GET | /api/tasks?status=completed | - | Get completed tasks |
| GET | /api/tasks/:id | - | Get task by ID |
| POST | /api/tasks | { title, description, priority } | Create task |
| PUT | /api/tasks/:id | { title, description, priority, status } | Update task |
| PATCH | /api/tasks/:id/status | { status: "pending" or "completed" } | Update status only |
| DELETE | /api/tasks/:id | - | Delete task |

## Features
- Add tasks with title, description, priority (low/medium/high)
- Retrieve all tasks or filter by status
- Update task status: pending or completed
- Full task update via PUT
- Delete tasks
- JSON responses for all endpoints
- Sample tasks pre-loaded on start
- Test with Postman or browser
