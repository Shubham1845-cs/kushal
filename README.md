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

### Step 2 - Node.js Projects
```bash
cd project10-nodejs-student  && npm install
cd project27-nodejs-library  && npm install
cd project34-blog-api        && npm install
cd project35-task-manager-api && npm install
```

### Step 3 - React Vite Projects
```bash
cd project16-currency-app      && npm install
cd project28-darkmode-app      && npm install
cd project29-clock-app         && npm install
cd project30-filter-app        && npm install
cd project31-notifications-app && npm install
```

### Step 4 - Spring Boot
```bash
cd project9-spring-inventory
mvn spring-boot:run
```

---

## All Projects

### Problems 1-8 (HTML/CSS/JS - Open index.html directly in browser)

| # | Question | Folder |
|---|----------|--------|
| 1 | Create a CV/Resume webpage using HTML and CSS | problem1-cv |
| 2 | Create a tabbed interface using HTML, CSS and JavaScript | problem2-tabs |
| 3 | Write JavaScript code to perform array operations | problem3-array-ops |
| 4 | Write a PHP program to collect form data and store it using sessions | problem4-form-session |
| 5 | Write a PHP program to connect to MySQL and perform database operations | problem5-php-mysql |
| 6 | Write a PHP program to calculate electricity bill based on units consumed | problem6-electricity-bill |
| 7 | Create a VIT result system to save and view student results using PHP | problem7-vit-result |
| 8 | Create a responsive feedback form with HTML and JavaScript validation | problem8-feedback-form |

---

### Project 9 - Spring Boot Inventory
**Question:** Develop a Spring Boot inventory management system using MongoDB.
**URL:** http://localhost:9090
**Run:** `mvn spring-boot:run`

---

### Project 10 - Node.js Student Registration
**Question:** Build a Node.js student registration system with Express and MySQL. Display student list in browser using an API endpoint.
**URL:** http://localhost:3000
**Run:** `npm install` then `node app.js`

---

### Project 11 - PHP Session Limit
**Question:** Write a PHP program to implement session management with a 5-minute session timeout.
**URL:** http://localhost:8081/project11-php-session-limit/login.php

---

### Project 12 - PHP Attendance System
**Question:** Create a PHP attendance system where students can register and teachers can take attendance online using checkboxes, roll number and name.
**URL:** http://localhost:8081/project12-php-attendance/student_register.php

---

### Project 13 - PHP Login System
**Question:** Write a PHP program to implement user registration and login with session-based authentication.
**URL:** http://localhost:8081/project13-php-login/login.php

---

### Project 14 - Spring Boot Bookstore
**Question:** Develop a Spring Boot bookstore application with user authentication and book management.
**URL:** http://localhost:8080

---

### Project 15 - PHP Complaint System
**Question:** Write a program in PHP for a complaint management system where students can make complaints and admin can manage them.
**URL:** http://localhost:8081/project15-php-complaint-system/student_login.php
**Admin:** username: admin, password: admin123

---

### Project 16 - React Currency Converter
**Question:** Develop a currency converter application using ReactJS that allows users to input an amount of dollars and convert it to rupees. Use React state and event handlers.
**URL:** http://localhost:5173
**Run:** `npm install` then `npm run dev`

---

### Project 17 - PHP Waste Collection
**Question:** Write a PHP program to collect waste like plastic or paper. System should accept location where waste is present and direct concerned authority to collect and manage the waste.
**URL:** http://localhost:8081/project17-php-waste-collection/index.php
**Authority Panel:** http://localhost:8081/project17-php-waste-collection/authority.php

---

### Project 18 - PHP Complaint Management (PMC/PMT)
**Question:** Write a program in PHP for a complaint management system where users can make complaints about services from organizations like PMC, PMT or any institution.
**URL:** http://localhost:8081/project18-php-complaint-mgmt/index.php

---

### Project 19 - PHP Airplane Seat Booking
**Question:** Write PHP code for booking seats in airplanes and display seating arrangements in airplanes.
**URL:** http://localhost:8081/project19-php-airplane-booking/index.php

---

### Project 20 - PHP Tic-Tac-Toe
**Question:** Write PHP code for Tic-Tac-Toe Game.
**URL:** http://localhost:8081/project20-php-tictactoe/index.php

---

### Project 21 - PHP Student CRUD
**Question:** Create a responsive website for showing EDIT and DELETE student records from database using PHP.
**URL:** http://localhost:8081/project21-php-student-crud/index.php

---

### Project 22 - HTML Canvas Drawing
**Question:** Create a responsive HTML document with canvas tag. Write JavaScript code which takes mouse click event for the point and draws any shape like line and rectangle.
**Run:** Open index.html directly in browser

---

### Project 23 - jQuery Style Switcher
**Question:** Write HTML JavaScript (jQuery) code for applying one style throughout all controls using one of three buttons. Each button causes a separate style for the entire page.
**Run:** Open index.html directly in browser

---

### Project 24 - Age Calculator
**Question:** Write JavaScript code to accept birth date and calculate age in year, month and date format.
**Run:** Open index.html directly in browser

---

### Project 25 - Traffic Signal
**Question:** Create a responsive website for showing Traffic signal lights. Use appropriate diagrams and glowing LEDs within HTML code.
**Run:** Open index.html directly in browser

---

### Project 27 - Node.js Library Management
**Question:** A library wants to store and manage book records in a Node.js application. Librarians should be able to add books and view all books. Create a Book table with fields book_id, title, author, year. Retrieve book data using a Node.js API and display in browser.
**URL:** http://localhost:4000
**Run:** `npm install` then `node app.js`

---

### Project 28 - React Dark Mode
**Question:** Design a React application that allows users to toggle between Light Mode and Dark Mode using React Hooks (useState).
**URL:** http://localhost:5173
**Run:** `npm install` then `npm run dev`

---

### Project 29 - React Digital Clock
**Question:** Develop a React application that displays a real-time digital clock which updates every second using React Hooks (useState, useEffect). Display time in HH:MM:SS format with start/stop option.
**URL:** http://localhost:5174
**Run:** `npm install` then `npm run dev`

---

### Project 30 - React Redux Product Filter
**Question:** Create a React application that allows users to filter products by category or price range using Redux to manage filter state.
**URL:** http://localhost:5176
**Run:** `npm install` then `npm run dev`

---

### Project 31 - React Redux Notifications
**Question:** Build a React application that displays system notifications using Redux to manage notification state. Allow users to add and dismiss notifications.
**URL:** http://localhost:5177
**Run:** `npm install` then `npm run dev`

---

### Project 34 - Blog Management REST API
**Question:** Create a blog management REST API using Express.js where users can create, read, update, and delete blog posts. Store blog data in database and return responses in JSON format.
**URL:** http://localhost:5000
**Run:** `npm install` then `node app.js` (requires XAMPP MySQL)

---

### Project 35 - Task Manager REST API
**Question:** Build a Task Manager REST API using Express.js to manage daily tasks. Create API routes for adding, retrieving, updating status and deleting tasks. Return task data in JSON format.
**URL:** http://localhost:5001
**Run:** `npm install` then `node app.js`
