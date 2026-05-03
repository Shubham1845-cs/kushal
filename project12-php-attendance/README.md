# Project 12 - PHP Attendance System

## Description
An online attendance management system where students can register and teachers can mark attendance using checkboxes.

## Tech Stack
- PHP, MySQL
- XAMPP Apache + MySQL

## Prerequisites
- XAMPP Apache running on port 8081
- XAMPP MySQL running

## Setup
Visit init_db.php first to create the database:
http://localhost:8081/project12-php-attendance/init_db.php

## URLs
- Student Register: http://localhost:8081/project12-php-attendance/student_register.php
- Teacher Attendance: http://localhost:8081/project12-php-attendance/teacher_attendance.php

## Database
- Database: `attendance_db`
- Tables: `students`, `attendance`
- View in phpMyAdmin: http://localhost:8081/phpmyadmin

## Features
- Student registration with roll number
- Teacher marks attendance by date using checkboxes
- Shows roll no, name, present/absent status
- Attendance statistics (total, present, absent)
- Date selector to view/edit past attendance
