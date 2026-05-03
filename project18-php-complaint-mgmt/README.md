# Project 18 - PHP Complaint Management System (PMC/PMT)

## Description
A complaint management system where users can submit complaints about services from organizations like PMC, PMT, MSEB etc.

## Tech Stack
- PHP, MySQL
- XAMPP Apache + MySQL

## Prerequisites
- XAMPP Apache running on port 8081
- XAMPP MySQL running

## Setup
Visit init_db.php first:
http://localhost:8081/project18-php-complaint-mgmt/init_db.php

## URL
http://localhost:8081/project18-php-complaint-mgmt/index.php

## Database
- Database: `complaint_mgmt_db`
- Table: `complaints`
- View in phpMyAdmin: http://localhost:8081/phpmyadmin

## Features
- Submit complaint with name, email, organization, category, subject, description
- Returns unique complaint ID on submission
- View all complaints in a table
- Status tracking: open, in-progress, resolved, closed
- Organizations: PMC, PMT, MSEB, Water Dept, Road Dept, Police, Other
