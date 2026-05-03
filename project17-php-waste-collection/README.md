# Project 17 - PHP Waste Collection System

## Description
A waste collection management system where users can submit waste collection requests and the authority can manage and update request statuses.

## Tech Stack
- PHP, MySQL
- XAMPP Apache + MySQL

## Prerequisites
- XAMPP Apache running on port 8081
- XAMPP MySQL running

## Setup
Visit init_db.php first:
http://localhost:8081/project17-php-waste-collection/init_db.php

## URLs
- Public Form: http://localhost:8081/project17-php-waste-collection/index.php
- Authority Panel: http://localhost:8081/project17-php-waste-collection/authority.php

## Database
- Database: `waste_db`
- Table: `waste_requests`
- View in phpMyAdmin: http://localhost:8081/phpmyadmin

## Features
- Submit waste collection request (type, location, contact)
- Authority panel with stats dashboard
- Filter requests by status
- Update status: Pending → Assigned → Collected
- Delete requests
