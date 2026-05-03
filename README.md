# WT Lab Projects

## Prerequisites - Install on new system

| Tool | Required For | Download |
|------|-------------|----------|
| XAMPP | PHP projects (11-21) | https://www.apachefriends.org |
| Node.js | Node/React projects (10,16,27-31,34,35) | https://nodejs.org |
| Java JDK 17+ | Spring Boot (9,14) | https://adoptium.net |
| Maven | Spring Boot (9,14) | https://maven.apache.org |
| MongoDB | Project 9 | https://www.mongodb.com |

---

## After Cloning - Setup Steps

### Step 1 - PHP Projects
Copy all `problem*` and `project11` to `project21` folders into:
```
C:\xampp\htdocs\
```
Start XAMPP → Apache (port 8081) + MySQL

Run `init_db.php` for these projects:
- http://localhost:8081/project12-php-attendance/init_db.php
- http://localhost:8081/project13-php-login/init_db.php
- http://localhost:8081/project15-php-complaint-system/init_db.php
- http://localhost:8081/project17-php-waste-collection/init_db.php
- http://localhost:8081/project18-php-complaint-mgmt/init_db.php
- http://localhost:8081/project19-php-airplane-booking/init_db.php
- http://localhost:8081/project21-php-student-crud/init_db.php

---

### Step 2 - Node.js Projects
Run `npm install` in each folder:
```bash
cd project10-nodejs-student && npm install
cd project27-nodejs-library && npm install
cd project34-blog-api       && npm install
cd project35-task-manager-api && npm install
```

---

### Step 3 - React Vite Projects
Run `npm install` in each folder:
```bash
cd project16-currency-app      && npm install
cd project28-darkmode-app      && npm install
cd project29-clock-app         && npm install
cd project30-filter-app        && npm install
cd project31-notifications-app && npm install
```

---

### Step 4 - Spring Boot Projects
```bash
cd project9-spring-inventory
mvn spring-boot:run
```
Requires: Java 17+, Maven, MongoDB running

---

## Project URLs

| # | Project | Type | URL |
|---|---------|------|-----|
| 1-8 | Problems | HTML/JS | Open index.html directly |
| 9 | Spring Inventory | Spring Boot | http://localhost:9090 |
| 10 | Node Student | Node.js | http://localhost:3000 |
| 11 | PHP Session | PHP | http://localhost:8081/project11-php-session-limit/login.php |
| 12 | PHP Attendance | PHP+MySQL | http://localhost:8081/project12-php-attendance/student_register.php |
| 13 | PHP Login | PHP+MySQL | http://localhost:8081/project13-php-login/login.php |
| 14 | Spring Bookstore | Spring Boot | http://localhost:8080 |
| 15 | PHP Complaint | PHP+MySQL | http://localhost:8081/project15-php-complaint-system/student_login.php |
| 16 | React Currency | React+Vite | npm run dev → http://localhost:5173 |
| 17 | PHP Waste | PHP+MySQL | http://localhost:8081/project17-php-waste-collection/index.php |
| 18 | PHP Complaint Mgmt | PHP+MySQL | http://localhost:8081/project18-php-complaint-mgmt/index.php |
| 19 | PHP Airplane | PHP+MySQL | http://localhost:8081/project19-php-airplane-booking/index.php |
| 20 | PHP TicTacToe | PHP | http://localhost:8081/project20-php-tictactoe/index.php |
| 21 | PHP Student CRUD | PHP+MySQL | http://localhost:8081/project21-php-student-crud/index.php |
| 22 | Canvas Drawing | HTML/JS | Open index.html directly |
| 23 | jQuery Styles | HTML/jQuery | Open index.html directly |
| 24 | Age Calculator | HTML/JS | Open index.html directly |
| 25 | Traffic Signal | HTML/JS | Open index.html directly |
| 27 | Node Library | Node.js+MySQL | http://localhost:4000 |
| 28 | React Dark Mode | React+Vite | npm run dev → http://localhost:5173 |
| 29 | React Clock | React+Vite | npm run dev → http://localhost:5174 |
| 30 | React Filter | React+Vite | npm run dev → http://localhost:5176 |
| 31 | React Notifications | React+Vite | npm run dev → http://localhost:5177 |
| 34 | Blog API | Node.js+MySQL | http://localhost:5000 |
| 35 | Task Manager API | Node.js | http://localhost:5001 |
